<?php
	session_start(); 
	global $li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5,$ls_disabled;

	$li_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$li_longestpro1= (25-$li_loncodestpro1)+1;
	$li_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$li_longestpro2= (25-$li_loncodestpro2)+1;
	$li_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$li_longestpro3= (25-$li_loncodestpro3)+1;
	$li_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$li_longestpro4= (25-$li_loncodestpro4)+1;
	$li_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$li_longestpro5= (25-$li_loncodestpro5)+1;
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_sep.php");
	$io_funciones_sep=new class_funciones_sep();
	require_once("sigesp_sep_c_solicitud.php");
	$io_solicitud=new sigesp_sep_c_solicitud("../../");
	// tipo de SEP si es de BIENES ó de SERVICIOS
	$ls_tipo=$io_funciones_sep->uf_obtenervalor("tipo","-");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_sep->uf_obtenervalor("proceso","");
	// total de filas de Bienes
	$li_totalbienes=$io_funciones_sep->uf_obtenervalor("totalbienes","1");
	// total de filas de Servicios
	$li_totalservicios=$io_funciones_sep->uf_obtenervalor("totalservicios","1");
	// total de filas de Servicios
	$li_totalconceptos=$io_funciones_sep->uf_obtenervalor("totalconceptos","1");
	// total de filas de Cargos
	$li_totalcargos=$io_funciones_sep->uf_obtenervalor("totalcargos","1");
	// total de filas de Cuentas
	$li_totalcuentas=$io_funciones_sep->uf_obtenervalor("totalcuentas","1");
	// total de filas de Cuentas cargos
	$li_totalcuentascargo=$io_funciones_sep->uf_obtenervalor("totalcuentascargo","1");
	// Indica si se deben cargar los cargos de un bien ó servicios ó si solo se deben pintar
	$ls_cargarcargos=$io_funciones_sep->uf_obtenervalor("cargarcargos","1");
	// Valor del Subtotal de la SEP
	$li_subtotal=$io_funciones_sep->uf_obtenervalor("subtotal","0,00");
	// Valor del Cargo de la SEP
	$li_cargos=$io_funciones_sep->uf_obtenervalor("cargos","0,00");
	// Valor del Total de la SEP
	$li_total=$io_funciones_sep->uf_obtenervalor("total","0,00");
	// Número de solicitud si se va a cargar
	$ls_numsol=$io_funciones_sep->uf_obtenervalor("numsol","");
	$ls_tipconpro = $io_funciones_sep->uf_obtenervalor("tipconpro","");
	$ls_titulo="";
	$la_cuentacargo[0]="";
	$li_cuenta=1;
	$ls_tipafeiva=$_SESSION["la_empresa"]["confiva"]; 
	switch($ls_proceso)
	{
		case "LIMPIAR":
		 
			switch(substr($ls_tipo,3,1))
			{
				case "B": // Bienes
					$ls_titulo="Bien o Material";
					uf_print_bienes($li_totalbienes);
					uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"B");
					break;
					
				case "S": // Servicios
					$ls_titulo="Servicios";
					uf_print_servicios($li_totalservicios);
					uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"S");
					break;
					
				case "O": // Conceptos
					$ls_titulo="Conceptos";
					uf_print_conceptos($li_totalconceptos);
					uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"O");
					break;
			}
			break;
			
		case "AGREGARBIENES":
			$ls_titulo="Bien o Material";
			uf_print_bienes($li_totalbienes,$ls_tipconpro);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"B");
			break;
			
		case "LOADBIENES":
			$ls_titulo="Bien o Material";
			uf_load_bienes($ls_numsol);
			uf_load_creditos($ls_titulo,$ls_numsol,"B");
			uf_load_cuentas($ls_numsol,"B");
			if ($ls_tipafeiva=='P')
			{
				uf_load_cuentas_cargo($ls_numsol,"B");
			}
			uf_load_total($li_subtotal,$li_cargos,$li_total);
			break;
			
		case "AGREGARSERVICIOS":
			$ls_titulo="Servicios";
			uf_print_servicios($li_totalservicios);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"S");
			break;

		case "LOADSERVICIOS":
			$ls_titulo="Servicios";
			uf_load_servicios($ls_numsol);
			uf_load_creditos($ls_titulo,$ls_numsol,"S");
			uf_load_cuentas($ls_numsol,"S");
			if ($ls_tipafeiva=='P')
			{
				uf_load_cuentas_cargo($ls_numsol,"S");
			}
			uf_load_total($li_subtotal,$li_cargos,$li_total);
			break;
			
		case "AGREGARCONCEPTOS":
			$ls_titulo="Conceptos";
			uf_print_conceptos($li_totalconceptos);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"O");
			break;

		case "LOADCONCEPTOS":
			$ls_titulo="Conceptos";
			uf_load_conceptos($ls_numsol);
			uf_load_creditos($ls_titulo,$ls_numsol,"O");
			uf_load_cuentas($ls_numsol,"O");
			if ($ls_tipafeiva=='P')
			{
				uf_load_cuentas_cargo($ls_numsol,"O");
			}
			uf_load_total($li_subtotal,$li_cargos,$li_total);
			break;
			
			case "AGREGARCUENTAS":
				switch(substr($ls_tipo,3,1))
				{
					case "B": // Bienes
						$ls_titulo="Bien o Material";
						uf_print_bienes($li_totalbienes);
						uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"B");
						uf_print_cierrecuentas_gasto($li_totalbienes,"B");
						if ($ls_tipafeiva=='P')
						{
							uf_print_cierrecuentas_cargo($li_totalcargos,$ls_cargarcargos,"B");
						}
						uf_print_total($li_totalbienes,"B");
						break;
						
					case "S": // Servicios
						$ls_titulo="Servicios";
						uf_print_servicios($li_totalservicios);
						uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"S");
						uf_print_cierrecuentas_gasto($li_totalservicios,"S");
						if ($ls_tipafeiva=='P')
						{
							uf_print_cierrecuentas_cargo($li_totalcargos,$ls_cargarcargos,"S");
						}
						uf_print_total($li_totalservicios,"S");
						break;
						
					case "O": // Conceptos
						$ls_titulo="Conceptos";
						uf_print_conceptos($li_totalconceptos);
						uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,$ls_tipconpro,"O");
						uf_print_cierrecuentas_gasto($li_totalconceptos,"O");
						if ($ls_tipafeiva=='P')
						{
							uf_print_cierrecuentas_cargo($li_totalcargos,$ls_cargarcargos,"O");
						}
						uf_print_total($li_totalconceptos,"O");
						break;
				}
				break;
			break;

	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los Bienes
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $io_solicitud;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad";
		$lo_title[5]="Precio/Unid.";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cargos"; 
		$lo_title[8]="Total";
		$lo_title[9]="";		
		// Recorrido de todos los Bienes del Grid
		$ls_estmodpart=$io_solicitud->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
			$lo_title[10]="";
		}			
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codart    = trim($io_funciones_sep->uf_obtenervalor("txtcodart".$li_fila,""));
			$ls_denart    = $io_funciones_sep->uf_obtenervalor("txtdenart".$li_fila,"");
			$li_canart    = $io_funciones_sep->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			$ls_unidad    = $io_funciones_sep->uf_obtenervalor("cmbunidad".$li_fila,"M");
			$li_preart    = $io_funciones_sep->uf_obtenervalor("txtpreart".$li_fila,"0,00");
			$li_subtotart = $io_funciones_sep->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
			$li_carart	  = $io_funciones_sep->uf_obtenervalor("txtcarart".$li_fila,"0,00");
			$li_totart	  = $io_funciones_sep->uf_obtenervalor("txttotart".$li_fila,"0,00");
			$ls_spgcuenta = $io_funciones_sep->uf_obtenervalor("txtspgcuenta".$li_fila,"");
			$li_unidad	  = $io_funciones_sep->uf_obtenervalor("txtunidad".$li_fila,"");	
			$ls_codpro	  = trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
			$ls_cuenta	  = trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,""));
			$ls_estcla	  = trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
			if($ls_unidad=="M") // Si es al Mayor
			{
				$ls_maysel="selected";
				$ls_detsel="";
			}
			else // Si es al Detal
			{
				$ls_maysel="";
				$ls_detsel="selected";
			}
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>
									 <input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codpro."' readonly>
									 <input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_cuenta."' readonly>
									 <input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."     value='".$li_unidad."'>";
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][9]="";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][10]="";
				}
			}
			else
			{
				$lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][10]="<a href=javascript:ue_cambiar_partida_bien('".$li_fila."','".$ls_codpro."','".$ls_cuenta."','".$ls_estcla."','1');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
				}
			}
			
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Bienes","gridbienes");
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los servicios
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $io_solicitud;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		$ls_estmodpart=$io_solicitud->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
			$lo_title[9]="";
		}		
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codser=$io_funciones_sep->uf_obtenervalor("txtcodser".$li_fila,"");
			$ls_denser=$io_funciones_sep->uf_obtenervalor("txtdenser".$li_fila,"");
			$li_canser=$io_funciones_sep->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			$li_preser=$io_funciones_sep->uf_obtenervalor("txtpreser".$li_fila,"0,00");
			$li_subtotser=$io_funciones_sep->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
			$li_carser=$io_funciones_sep->uf_obtenervalor("txtcarser".$li_fila,"0,00");
			$li_totser=$io_funciones_sep->uf_obtenervalor("txttotser".$li_fila,"0,00");
			$ls_spgcuenta=$io_funciones_sep->uf_obtenervalor("txtspgcuenta".$li_fila,"");
			///---------campos relacionados al gasto----------------------------------------
			$ls_codproser=trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
			$ls_cuentaser=trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,""));
			$ls_estclaser=trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
			//-------------------------------------------------------------------------------	
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>
									 <input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codproser."' readonly>
									 <input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_cuentaser."' readonly>
									 <input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estclaser."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][8]="";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][9]="";
				}
			}
			else
			{
				$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][9]="<a href=javascript:ue_cambiar_partida_servicio('".$li_fila."','".$ls_codproser."','".$ls_cuentaser."','".$ls_estclaser."','3');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
				}
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_conceptos($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_conceptos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los conceptos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep,$io_solicitud;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";
        $ls_estmodpart=$io_solicitud->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
			$lo_title[9]="";
		}		
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codcon	  = $io_funciones_sep->uf_obtenervalor("txtcodcon".$li_fila,"");
			$ls_dencon	  = $io_funciones_sep->uf_obtenervalor("txtdencon".$li_fila,"");
			$ld_cancon	  = $io_funciones_sep->uf_obtenervalor("txtcancon".$li_fila,"0,00");   
			$ld_precon	  = $io_funciones_sep->uf_obtenervalor("txtprecon".$li_fila,"0,00");    
			$ld_subtotcon = $io_funciones_sep->uf_obtenervalor("txtsubtotcon".$li_fila,"0,00");
			$ld_totcon	  = $io_funciones_sep->uf_obtenervalor("txttotcon".$li_fila,"0,00");    
			$ld_carcon    = $io_funciones_sep->uf_obtenervalor("txtcarcon".$li_fila,"0,00");
			$ls_spgcuenta = $io_funciones_sep->uf_obtenervalor("txtspgcuenta".$li_fila,"");		
			///---------campos relacionados al gasto----------------------------------------
			$ls_codprocon=trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
			$ls_cuentacon=trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,"")); 
			$ls_estclacon=trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
			//-------------------------------------------------------------------------------	
			$lo_object[$li_fila][1]="<input name=txtcodcon".$li_fila."     type=text id=txtcodcon".$li_fila."     class=sin-borde   size=15 value='".$ls_codcon."'     style=text-align:center readonly>
									 <input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codprocon."' readonly>
									 <input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_cuentacon."' readonly>
									 <input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estclacon."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtdencon".$li_fila."     type=text id=txtdencon".$li_fila."     class=sin-borde   size=30 value='".$ls_dencon."'     style=text-align:left   readonly>";
			$lo_object[$li_fila][3]="<input name=txtcancon".$li_fila."     type=text id=txtcancon".$li_fila."     class=sin-borde   size=9  value='".$ld_cancon."'     style=text-align:right onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][4]="<input name=txtprecon".$li_fila."     type=text id=txtprecon".$li_fila."     class=sin-borde   size=15 value='".$ld_precon."'     style=text-align:right  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input name=txtsubtotcon".$li_fila."  type=text id=txtsubtotcon".$li_fila."  class=sin-borde   size=15 value='".$ld_subtotcon."'  style=text-align:right  readonly>";
			$lo_object[$li_fila][6]="<input name=txtcarcon".$li_fila."     type=text id=txtcarcon".$li_fila."     class=sin-borde   size=10 value='".$ld_carcon."'     style=text-align:right  readonly>";
			$lo_object[$li_fila][7]="<input name=txttotcon".$li_fila."     type=text id=txttotcon".$li_fila."     class=sin-borde   size=15 value='".$ld_totcon."'     style=text-align:right  readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta value='".$ls_spgcuenta."'>";
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][8]="";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][9]="";
				}
			}
			else
			{
				$lo_object[$li_fila][8]="<a href=javascript:ue_delete_conceptos('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=10 border=0></a>";
				if ($ls_estmodpart==1)
				{
					$lo_object[$li_fila][9]="<a href=javascript:ue_cambiar_partida_conceptos('".$li_fila."','".$ls_codprocon."','".$ls_cuentacon."','".$ls_estclacon."','4');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
				}
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoconceptos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Conceptos'>Agregar Detalle Conceptos</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Conceptos","gridconceptos");
	}// end function uf_print_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos($as_titulo,$ai_total,$as_cargarcargos,$as_tipconpro,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_cargarcargos // Si cargamos los cargos ó solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de créditos y busca los creditos de un Bien, un Servicio ò un concepto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo, $li_cuenta, $io_solicitud;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto del Cargo";
		$lo_title[6]="Sub-Total";
		$ls_estmodpart=$io_solicitud->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
			$lo_title[7]="";
		}	
		$lo_object[0]="";		
		// Recorrido de el grid de Cargos
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codservic  = $io_funciones_sep->uf_obtenervalor("txtcodservic".$li_fila,"");
			$ls_codcar	   = $io_funciones_sep->uf_obtenervalor("txtcodcar".$li_fila,"");
			$ls_dencar	   = $io_funciones_sep->uf_obtenervalor("txtdencar".$li_fila,"");
			$li_bascar	   = $io_funciones_sep->uf_obtenervalor("txtbascar".$li_fila,"");
			$li_moncar	   = $io_funciones_sep->uf_obtenervalor("txtmoncar".$li_fila,"");
			$li_subcargo   = $io_funciones_sep->uf_obtenervalor("txtsubcargo".$li_fila,"");
			$ls_spg_cuenta = $io_funciones_sep->uf_obtenervalor("cuentacargo".$li_fila,"");
			$ls_formula    = $io_funciones_sep->uf_obtenervalor("formulacargo".$li_fila,"");
			$ls_codpro	   = trim($io_funciones_sep->uf_obtenervalor("txtcodgascre".$li_fila,"")); 
			$ls_cuenta	   = trim($io_funciones_sep->uf_obtenervalor("txtcodspgcre".$li_fila,""));
			$ls_estcla	   = trim($io_funciones_sep->uf_obtenervalor("txtstatuscre".$li_fila,""));
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>
									 <input type=hidden name=txtcodgascre".$li_fila." id=txtcodgascre".$li_fila."  value='".$ls_codpro."' readonly>
									 <input type=hidden name=txtcodspgcre".$li_fila." id=txtcodspgcre".$li_fila."  value='".$ls_cuenta."' readonly>
									 <input type=hidden name=txtstatuscre".$li_fila." id=txtstatuscre".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>
									 <input name=codcargo".$li_fila." type=hidden id=codcargo".$li_fila." value='".$ls_codcar."'>";
			if ($ls_estmodpart==1)
            {
				$lo_object[$li_fila][7]="<a href=javascript:ue_cambiar_creditos('".$li_fila."','".$ls_codpro."','".$ls_cuenta."','".$ls_estcla."','2');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
			}
		}
		if($as_cargarcargos=="1")
		{	// Si se deben cargar los cargos Buscamos el Código del último Bien cargado 
			// y obtenemos los cargos de dicho Bien
		  if($as_tipconpro!="F")
		  {  
				require_once("sigesp_sep_c_solicitud.php");
				$io_solicitud=new sigesp_sep_c_solicitud("../../");
				$ls_codigo		 = $io_funciones_sep->uf_obtenervalor("txtcodservic","");
				$ls_codprounidad = $io_funciones_sep->uf_obtenervalor("codprounidad","");
				$ls_estcla       = $io_funciones_sep->uf_obtenervalor("estcla","");
				switch ($as_tipo)
				{
					case "B":
						$rs_data = $io_solicitud->uf_load_cargosbienes($ls_codigo,$ls_codprounidad,$ls_estcla);
						break;
					case "S":
						$rs_data = $io_solicitud->uf_load_cargosservicios($ls_codigo,$ls_codprounidad,$ls_estcla);
						break;
					case "O":
						$rs_data = $io_solicitud->uf_load_cargosconceptos($ls_codigo,$ls_codprounidad,$ls_estcla);
						break;
				}
				while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
				{
					$lb_existecargo  = true;
					$ls_codservic    = $row["codigo"];
					$ls_codcar       = $row["codcar"];
					$ls_dencar       = $row["dencar"];
					$ls_spg_cuenta   = trim($row["spg_cuenta"]);
					$ls_formula      = $row["formula"];
					$li_bascar       = "0,00";
					$li_moncar       = "0,00";
					$li_subcargo     = "0,00";
					$ls_existecuenta = $row["existecuenta"];
					
					if($ls_spg_cuenta!="")
					{// Si la cuenta presupuestaria es diferente de blanco llenamos un arreglo de cuentas
						$la_cuentacargo[$li_cuenta]["cargo"]=$ls_codcar;
						$la_cuentacargo[$li_cuenta]["cuenta"]=$ls_spg_cuenta;
						if($ls_existecuenta==0)
						{
							$la_cuentacargo[$li_cuenta]["programatica"]="";
							$la_cuentacargo[$li_cuenta]["estcla"]=$ls_estcla;	
						}
						else
						{
							$la_cuentacargo[$li_cuenta]["programatica"]=$ls_codprounidad;
							$la_cuentacargo[$li_cuenta]["estcla"]=$ls_estcla;						
						}
						$li_cuenta++;
					}
					
					$ai_total++;
					///---------campos relacionados al gasto----------------------------------------
					$ls_codpro=trim($io_funciones_sep->uf_obtenervalor("txtcodprocar".$ai_total,"")); 
					$ls_cuenta=trim($io_funciones_sep->uf_obtenervalor("txtcuentacar".$ai_total,""));
					$ls_estcla=trim($io_funciones_sep->uf_obtenervalor("txtestclacar".$ai_total,""));
					//------------------------------------------------------------------------------
					if ($as_cargarcargos=="1")
					   { // si los cargos se deben cargar recorremos el arreglo de cuentas
						 // que se lleno con los cargos 
						 $ls_estcla ="";
						 $li_cuenta=count($la_cuentacargo);
						 for ($li_fila2=1;($li_fila2<$li_cuenta);$li_fila2++)
							 {
							   $ls_cuenta       = trim($la_cuentacargo[$li_fila2]["cuenta"]);
							   $ls_programatica = trim($la_cuentacargo[$li_fila2]["programatica"]);
							   $ls_estcla       = $la_cuentacargo[$li_fila2]["estcla"];						
							 }
						 $ls_codpro=$ls_programatica; 
						 $ls_cuenta=$ls_cuenta;
						 $ls_estcla=$ls_estcla;
					   }
					$lo_object[$ai_total][1]="<input name=txtcodservic".$ai_total." type=text id=txtcodservic".$ai_total." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>
											  <input type=hidden name=txtcodgascre".$ai_total." id=txtcodgascre".$ai_total."  value='".$ls_codpro."' readonly>
											  <input type=hidden name=txtcodspgcre".$ai_total." id=txtcodspgcre".$ai_total."  value='".$ls_cuenta."' readonly>
											  <input type=hidden name=txtstatuscre".$ai_total." id=txtstatuscre".$ai_total."  value='".$ls_estcla."' readonly>";
					$lo_object[$ai_total][2]="<input name=txtcodcar".$ai_total."    type=text id=txtcodcar".$ai_total."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
					$lo_object[$ai_total][3]="<input name=txtdencar".$ai_total."    type=text id=txtdencar".$ai_total."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
					$lo_object[$ai_total][4]="<input name=txtbascar".$ai_total."    type=text id=txtbascar".$ai_total."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
					$lo_object[$ai_total][5]="<input name=txtmoncar".$ai_total."    type=text id=txtmoncar".$ai_total."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
					$lo_object[$ai_total][6]="<input name=txtsubcargo".$ai_total."  type=text id=txtsubcargo".$ai_total."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
											 "<input name=cuentacargo".$ai_total."  type=hidden id=cuentacargo".$ai_total."  value='".$ls_spg_cuenta."'>".
											 "<input name=formulacargo".$ai_total." type=hidden id=formulacargo".$ai_total." value='".$ls_formula."'>
											  <input name=codcargo".$ai_total." type=hidden id=codcargo".$ai_total." value='".$ls_codcar."'>";
					if ($ls_estmodpart==1)
					{
						$lo_object[$ai_total][7]="<a href=javascript:ue_cambiar_creditos('".$ai_total."','".$ls_codpro."','".$ls_cuenta."','".$ls_estcla."','2');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
					}
				}
			}
		}		
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cr&eacute;ditos","gridcreditos");
		unset($io_solicitud);		
		print "<table width='840' height='22' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "        <tr>";
		print "          <td width='175' height='22' align='right'><div align='left'><input name='btncrear' type='button' class='boton' id='btncerrar' value='Crear Asiento' onClick='javascript: ue_crear_asiento();'></div></td>";
		print "        </tr>";
		print "</table>";
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_gasto($ai_total,$as_tipo,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_gasto
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();		
		// Titulos el Grid
		$lo_title[1]="Estructura Programatica";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codpro=trim($io_funciones_sep->uf_obtenervalor("txtcodprogas".$li_fila,""));
			$ls_cuenta=trim($io_funciones_sep->uf_obtenervalor("txtcuentagas".$li_fila,""));
			$ls_estcla=trim($io_funciones_sep->uf_obtenervalor("txtestclagas".$li_fila,""));
			
			$li_moncue=trim($io_funciones_sep->uf_obtenervalor("txtmoncuegas".$li_fila,"0,00"));
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);							
			if($ls_cuenta!="")
			{
				$io_dscuentas->insertRow("codprogas",$ls_codpro);	
				$io_dscuentas->insertRow("estclagas",$ls_estcla);
				$io_dscuentas->insertRow("cuentagas",$ls_cuenta);			
				$io_dscuentas->insertRow("moncuegas",$li_moncue);			
			}
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codprogas','1'=>'cuentagas'),array('0'=>'moncuegas'),'moncuegas');
		$li_total=$io_dscuentas->getRowCount('codprogas');
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codpro=$io_dscuentas->getValue('codprogas',$li_fila);
			$ls_cuenta=$io_dscuentas->getValue('cuentagas',$li_fila);
			$ls_estcla=$io_dscuentas->getValue('estclagas',$li_fila);
			$li_moncue=number_format($io_dscuentas->getValue('moncuegas',$li_fila),2,",",".");
			$ls_codest1=substr($ls_codpro,0,$li_loncodestpro1);
			$ls_codest2=substr($ls_codpro,$li_loncodestpro1,$li_loncodestpro2);
			$ls_codest3=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2,$li_loncodestpro3);
			$ls_codest4=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3,$li_loncodestpro4);
			$ls_codest5=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+$li_loncodestpro4,$li_loncodestpro5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>"."<input name=txtestclagas".$li_fila."  type=hidden size='2' id=txtestclagas".$li_fila."  value='".$ls_estcla."'>";
				$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' ><input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_programatica."'>";
				/*$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
										"<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_programatica."'>";*/
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtprogramaticagas".$ai_total." type=text id=txtprogramaticagas".$ai_total." class=sin-borde  style=text-align:center size=45 value='' readonly>"."<input name=txtestclagas".$li_fila."  type=hidden size='2' id=txtestclagas".$li_fila."  value=''>";;
		$lo_object[$ai_total][2]="<input name=txtcuentagas".$ai_total."       type=text id=txtcuentagas".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtmoncuegas".$ai_total."       type=text id=txtmoncuegas".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtcodprogas".$ai_total."       type=hidden id=txtcodprogas".$ai_total."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0' style='display:none'> ";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_dscuentas);
	}// end function uf_print_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargo($ai_total,$as_cargarcargos,$as_tipo,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_cargarcargos // Si cargamos los cargos ó solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();		
		// Titulos el Grid
		$lo_title[1]="Cargo";
		$lo_title[2]="Estructura Programatica";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		//$lo_title[5]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias del Cargo
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_cargo  = trim($io_funciones_sep->uf_obtenervalor("txtcodcargo".$li_fila,""));
			$ls_estcla = trim($io_funciones_sep->uf_obtenervalor("txtestclacar".$li_fila,"")); 
			$ls_codpro = trim($io_funciones_sep->uf_obtenervalor("txtcodprocar".$li_fila,""));
			$ls_cuenta = trim($io_funciones_sep->uf_obtenervalor("txtcuentacar".$li_fila,""));
			$li_moncue = trim($io_funciones_sep->uf_obtenervalor("txtmoncuecar".$li_fila,"0,00"));
			$li_moncue = str_replace(".","",$li_moncue);
			$li_moncue = str_replace(",",".",$li_moncue);							
			if($ls_cuenta!="")
			{
				$io_dscuentas->insertRow("codcargo",$ls_cargo);			
				$io_dscuentas->insertRow("codprocar",$ls_codpro);			
				$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
				$io_dscuentas->insertRow("moncuecar",$li_moncue);
				$io_dscuentas->insertRow("estclacar",$ls_estcla);			
			}			
		}
		if($as_cargarcargos=="1")
		{	// si los cargos se deben cargar recorremos el arreglo de cuentas
			// que se lleno con los cargos 
			$li_cuenta=count($la_cuentacargo)-1;
			for($li_fila=1;($li_fila<=$li_cuenta);$li_fila++)
			{
				$ls_cargo        = trim($la_cuentacargo[$li_fila]["cargo"]); 
				$ls_cuenta       = trim($la_cuentacargo[$li_fila]["cuenta"]);
				$ls_programatica = trim($la_cuentacargo[$li_fila]["programatica"]);
				$ls_estcla       = $la_cuentacargo[$li_fila]["estcla"];
				$li_moncue="0.00";
				if($ls_cuenta!="")
				{
					$io_dscuentas->insertRow("codcargo",$ls_cargo);			
					$io_dscuentas->insertRow("codprocar",$ls_programatica);	
					$io_dscuentas->insertRow("estclacar",$ls_estcla);		
					$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
					$io_dscuentas->insertRow("moncuecar",$li_moncue);
				}			
			}
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codprocar','2'=>'cuentacar'),array('0'=>'moncuecar'),'moncuecar');
		$li_total=$io_dscuentas->getRowCount('codcargo');	
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_cargo     = $io_dscuentas->getValue('codcargo',$li_fila);
			$ls_codpro    = $io_dscuentas->getValue('codprocar',$li_fila);
			$ls_cuenta    = $io_dscuentas->getValue('cuentacar',$li_fila);
			$ls_estclacar = $io_dscuentas->getValue('estclacar',$li_fila);
			$li_moncue    = number_format($io_dscuentas->getValue('moncuecar',$li_fila),2,",",".");
			
			$ls_codest1=substr($ls_codpro,0,$li_loncodestpro1); 
			$ls_codest2=substr($ls_codpro,$li_loncodestpro1,$li_loncodestpro2);
			$ls_codest3=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2,$li_loncodestpro3);
			$ls_codest4=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3,$li_loncodestpro4);
			$ls_codest5=substr($ls_codpro,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+$li_loncodestpro4,$li_loncodestpro5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$ls_cargo."' readonly>";
				$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>".
										"<input name=txtestclacar".$li_fila."       type=hidden size='2' id=txtestclacar".$li_fila."  value='".$ls_estclacar."'>";
				$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >".
										"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codpro."'>";
				/*$lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
										"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codpro."'>";*/
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtcodcargo".$ai_total." type=text id=txtcodcargo".$ai_total." class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtprogramaticacar".$ai_total." type=text id=txtprogramaticacar".$ai_total." class=sin-borde  style=text-align:center size=45 value='' readonly>"."<input name=txtestclacar".$li_fila."  type=hidden size='2' id=txtestclacar".$li_fila."  value=''>";
		$lo_object[$ai_total][3]="<input name=txtcuentacar".$ai_total."       type=text id=txtcuentacar".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtmoncuecar".$ai_total."       type=text id=txtmoncuecar".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][5]="<input name=txtcodprocar".$ai_total."       type=hidden id=txtcodprocar".$ai_total."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0' style='display:none' >";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Cargos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas Cargos","gridcuentascargos");
		unset($io_dscuentas);
	}// end function uf_print_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totrowitem,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal // Valor del subtotal
		//				   ai_cargos // Valor total de los cargos
		//				   ai_total // Total de la solicitu de pago
		//	  Description: Método que imprime los totales de la SEP
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_sep;
		$li_totalgasto=0;
		$li_totalcargo=0;
		$li_totalgeneral=0;
		switch($as_tipsol)
		{
			case "B":
				for($li_i=1;$li_i<$ai_totrowitem;$li_i++)
				{
				
					$li_subtotart = $io_funciones_sep->uf_obtenervalor("txtsubtotart".$li_i,"0,00");
					$li_carart	  = $io_funciones_sep->uf_obtenervalor("txtcarart".$li_i,"0,00");
					$li_totart	  = $io_funciones_sep->uf_obtenervalor("txttotart".$li_i,"0,00");
					$li_subtotart= str_replace(".","",$li_subtotart);
					$li_subtotart= str_replace(",",".",$li_subtotart);	
					$li_carart= str_replace(".","",$li_carart);
					$li_carart= str_replace(",",".",$li_carart);	
					$li_totart= str_replace(".","",$li_totart);
					$li_totart= str_replace(",",".",$li_totart);	
					$li_totalgasto=$li_totalgasto+$li_subtotart;
					$li_totalcargo=$li_totalcargo+$li_carart;
					$li_totalgeneral=$li_totalgeneral+$li_totart;
				}
			break;
			case "S":
				for($li_i=1;$li_i<$ai_totrowitem;$li_i++)
				{
				
					$li_subtotser=$io_funciones_sep->uf_obtenervalor("txtsubtotser".$li_i,"0,00");
					$li_carser=$io_funciones_sep->uf_obtenervalor("txtcarser".$li_i,"0,00");
					$li_totser=$io_funciones_sep->uf_obtenervalor("txttotser".$li_i,"0,00");
					$li_subtotser= str_replace(".","",$li_subtotser);
					$li_subtotser= str_replace(",",".",$li_subtotser);	
					$li_carser= str_replace(".","",$li_carser);
					$li_carser= str_replace(",",".",$li_carser);	
					$li_totser= str_replace(".","",$li_totser);
					$li_totser= str_replace(",",".",$li_totser);	
					$li_totalgasto=$li_totalgasto+$li_subtotser;
					$li_totalcargo=$li_totalcargo+$li_carser;
					$li_totalgeneral=$li_totalgeneral+$li_totser;
				}
			break;
			case "O":
				for($li_i=1;$li_i<$ai_totrowitem;$li_i++)
				{
					$li_subtotcon = $io_funciones_sep->uf_obtenervalor("txtsubtotcon".$li_i,"0,00");
					$li_totcon	  = $io_funciones_sep->uf_obtenervalor("txttotcon".$li_i,"0,00");    
					$li_carcon    = $io_funciones_sep->uf_obtenervalor("txtcarcon".$li_i,"0,00");
					$li_subtotcon= str_replace(".","",$li_subtotcon);
					$li_subtotcon= str_replace(",",".",$li_subtotcon);	
					$li_carcon= str_replace(".","",$li_carcon);
					$li_carcon= str_replace(",",".",$li_carcon);	
					$li_totcon= str_replace(".","",$li_totcon);
					$li_totcon= str_replace(",",".",$li_totcon);	
					$li_totalgasto=$li_totalgasto+$li_subtotcon;
					$li_totalcargo=$li_totalcargo+$li_carcon;
					$li_totalgeneral=$li_totalgeneral+$li_totcon;
				}
			break;
		}
		$li_totalgasto=number_format($li_totalgasto,2,',','.');
		$li_totalcargo=number_format($li_totalcargo,2,',','.');
		$li_totalgeneral=number_format($li_totalgeneral,2,',','.');
		print "<table width='840' height='116' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
		print "        <tr class='titulo-celdanew'>";
		print "          <td height='22' colspan='4'><div align='center'>Totales</div></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='128' height='13'>&nbsp;</td>";
		print "          <td width='113' height='13' align='left'></td>";
		print "          <td width='368' height='13' align='right'><div align='right'></div></td>";
		print "          <td width='239' height='13' align='left'>&nbsp;</td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><strong>Subtotal&nbsp;&nbsp;</strong></td>";
		print "          <td height='22'><input name='txtsubtotal'  type='text' class='titulo-conect' id='txtsubtotal' style='text-align:right' value='".$li_totalgasto."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><div align='right'><strong>Otros Cr&eacute;ditos&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txtcargos' type='text' class='titulo-conect' id='txtcargos' style='text-align:right' value='".$li_totalcargo."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotal' type='text' class='titulo-conect' id='txttotal' style='text-align:right' value='".$li_totalgeneral."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
	}// end function uf_print_total
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_total($ai_subtotal,$ai_cargos,$ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_total
		//		   Access: private
		//	    Arguments: ai_subtotal // Valor del subtotal
		//				   ai_cargos // Valor total de los cargos
		//				   ai_total // Total de la solicitu de pago
		//	  Description: Método que imprime los totales de la SEP
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='840' height='116' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
		print "        <tr class='titulo-celdanew'>";
		print "          <td height='22' colspan='4'><div align='center'>Totales</div></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='128' height='13'>&nbsp;</td>";
		print "          <td width='113' height='13' align='left'></td>";
		print "          <td width='368' height='13' align='right'><div align='right'></div></td>";
		print "          <td width='239' height='13' align='left'>&nbsp;</td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><strong>Subtotal&nbsp;&nbsp;</strong></td>";
		print "          <td height='22'><input name='txtsubtotal'  type='text' class='titulo-conect' id='txtsubtotal' style='text-align:right' value='".$ai_subtotal."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><div align='right'><strong>Otros Cr&eacute;ditos&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txtcargos' type='text' class='titulo-conect' id='txtcargos' style='text-align:right' value='".$ai_cargos."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotal' type='text' class='titulo-conect' id='txttotal' style='text-align:right' value='".$ai_total."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
	}// end function uf_print_total
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los bienes de la solicitud y los imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad";
		$lo_title[5]="Precio/Unid.";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cargos"; 
		$lo_title[8]="Total";
		$lo_title[9]="";
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_bienes($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codart=$row["codart"];
			$ls_denart=utf8_encode($row["denart"]);
			$ls_unidad=$row["unidad"];
			$li_canart=$row["canart"];
			$li_preart=$row["monpre"];
			$li_totart=$row["monart"];
			$ls_spgcuenta=$row["spg_cuenta"];
			$li_unimed=$row["unimed"];
			$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_estcla=$row["estcla"];
			if($ls_unidad=="M") // Si es al Mayor
			{
				$ls_maysel="selected";
				$ls_detsel="";
				$li_subtotart=$li_preart*($li_canart*$li_unimed);
			}
			else // Si es al Detal
			{
				$ls_maysel="";
				$ls_detsel="selected";
				$li_subtotart=$li_preart*$li_canart;
			}
			$li_totart=number_format($li_totart,2,".","");
			$li_subtotart=number_format($li_subtotart,2,".","");
			$li_carart=$li_totart-$li_subtotart;
			$li_subtotart=number_format($li_subtotart,2,",",".");
			$li_totart=number_format($li_totart,2,",",".");
			$li_canart=number_format($li_canart,2,",",".");
			$li_preart=number_format($li_preart,2,",",".");
			$li_carart=number_format($li_carart,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>
									 <input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codpro."' readonly>
									 <input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>
									 <input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."     value='".$li_unimed."'>";
			$lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value=''  readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value=''  readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value=''   onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
		$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
		$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value=''  readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  readonly>";
		$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value=''  readonly>".
								" <input type=hidden name=txtspgcuenta".$li_fila."  value=''> ".
								" <input type=hidden name=txtunidad".$li_fila."     value=''>";
		$lo_object[$li_fila][9]="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_solicitud);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Bienes","gridbienes");
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los servicios de la solicitud y los imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_servicios($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codser=$row["codser"];
			$ls_denser=utf8_encode($row["denser"]);
			$li_canser=$row["canser"];
			$li_preser=$row["monpre"];
			$li_subtotser=$li_preser*$li_canser;
			$li_totser=$row["monser"];
			$li_totser=number_format($li_totser,2,".","");
			$li_subtotser=number_format($li_subtotser,2,".","");
			$li_carser=$li_totser-$li_subtotser;
			$ls_spgcuenta=$row["spg_cuenta"];
			$ls_codproser=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_estclaser=$row["estcla"];
			$li_canser=number_format($li_canser,2,",",".");
			$li_preser=number_format($li_preser,2,",",".");
			$li_subtotser=number_format($li_subtotser,2,",",".");
			$li_carser=number_format($li_carser,2,",",".");
			$li_totser=number_format($li_totser,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>
									 <input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codproser."' readonly>
									 <input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>
									 <input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estclaser."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
		$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''> ";
		$lo_object[$li_fila][8] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_solicitud);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos($as_numsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los conceptos de la solicitud y los imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cargos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_conceptos($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codcon=$row["codconsep"];
			$ls_dencon=utf8_encode($row["denconsep"]);
			$li_cancon=$row["cancon"];
			$li_precon=$row["monpre"];
			$li_subtotcon=$li_precon*$li_cancon;
			$li_totcon=$row["moncon"];
			$li_totcon=number_format($li_totcon,2,".","");
			$li_subtotcon=number_format($li_subtotcon,2,".","");
			$li_carcon=$li_totcon-$li_subtotcon;
			$ls_spgcuenta=$row["spg_cuenta"];
			$ls_codproser=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_estclaser=$row["estcla"];
			$li_cancon=number_format($li_cancon,2,",",".");
			$li_precon=number_format($li_precon,2,",",".");
			$li_subtotcon=number_format($li_subtotcon,2,",",".");
			$li_carcon=number_format($li_carcon,2,",",".");
			$li_totcon=number_format($li_totcon,2,",",".");
			$lo_object[$li_fila][1]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codcon."' readonly>".
									"<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codproser."' readonly>".
									"<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
									"<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estclaser."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdencon".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_dencon."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtcancon".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_cancon."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtprecon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_precon."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotcon".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotcon."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarcon".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carcon."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotcon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totcon."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_conceptos('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly>";
		$lo_object[$li_fila][2]="<input type=text name=txtdencon".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input type=text name=txtcancon".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtprecon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('O','".$li_fila."');>";
		$lo_object[$li_fila][5]="<input type=text name=txtsubtotcon".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][6]="<input type=text name=txtcarcon".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txttotcon".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''> ";
		$lo_object[$li_fila][8] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogoconceptos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Conceptos'>Agregar Detalle Conceptos</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Conceptos","gridconceptos");
	}// end function uf_load_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_creditos($as_titulo,$as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca los creditos de una solicitud y las imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo, $li_cuenta;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto del Cargo";
		$lo_title[6]="Sub-Total";
		$lo_object[0]="";
		switch($as_tipo)
		{
			case "B": // Si es de Bienes
				$ls_tabla = "sep_dta_cargos";
				$ls_campo = "codart";
				break;
			case "S": // Si es de Servicios
				$ls_tabla = "sep_dts_cargos";
				$ls_campo = "codser";
				break;
			case "O": // Si es de Conceptos
				$ls_tabla = "sep_dtc_cargos";
				$ls_campo = "codconsep";
				break;
		}
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_cargos($as_numsol,$ls_tabla,$ls_campo);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codservic=$row["codigo"];
			$ls_codcar=$row["codcar"];
			$ls_dencar=utf8_encode($row["dencar"]);
			$li_bascar=number_format($row["monbasimp"],2,",",".");
			$li_moncar=number_format($row["monimp"],2,",",".");
			$li_subcargo=number_format($row["monto"],2,",",".");
			$ls_spg_cuenta=$row["spg_cuenta"];
			$ls_formula=$row["formula"];
			$ls_codestpro1=$row["codestpro1"];
			$ls_codestpro2=$row["codestpro2"];
			$ls_codestpro3=$row["codestpro3"];
			$ls_codestpro4=$row["codestpro4"];
			$ls_codestpro5=$row["codestpro5"];
			$ls_estcla=$row["estcla"];
			$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>".
									"<input type=hidden name=txtcodgascre".$li_fila." id=txtcodgascre".$li_fila."  value='".$ls_codestpro."' readonly>".
									"<input type=hidden name=txtcodspgcre".$li_fila." id=txtcodspgcre".$li_fila."  value='".$ls_spg_cuenta."' readonly>".
									"<input type=hidden name=txtstatuscre".$li_fila." id=txtstatuscre".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
		}
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cr&eacute;ditos","gridcreditos");
		unset($io_solicitud);		
		print "<table width='840' height='22' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "        <tr>";
		print "          <td width='175' height='22' align='right'><div align='left'><input name='btncrear' type='button' class='boton' id='btncerrar' value='Crear Asiento' onClick='javascript: ue_crear_asiento();'></div></td>";
		print "        </tr>";
		print "</table>";
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca las cuentas presupuestarias asociadas a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo;
		global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
		global $li_longestpro1,$li_longestpro2,$li_longestpro3,$li_longestpro4,$li_longestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Estructura Programatica";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		$lo_title[4]=""; 
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$io_dscuentas = $io_solicitud->uf_load_cuentas($as_numsol);
		$li_fila=0;
		if($io_dscuentas!=false)
		{
			$li_totrow=$io_dscuentas->getRowCount("spg_cuenta");
			for($li_i=1;($li_i<=$li_totrow);$li_i++)
			{
				$li_monto=$io_dscuentas->data["total"][$li_i];
				if($li_monto>0)
				{
					$li_fila=$li_fila+1;
					$ls_codpro=$io_dscuentas->data["codestpro1"][$li_i].$io_dscuentas->data["codestpro2"][$li_i].
							   $io_dscuentas->data["codestpro3"][$li_i].$io_dscuentas->data["codestpro4"][$li_i].
							   $io_dscuentas->data["codestpro5"][$li_i];
					$ls_cuenta=$io_dscuentas->data["spg_cuenta"][$li_i];
					$ls_estcla=$io_dscuentas->data["estcla"][$li_i];
					$li_moncue=number_format($io_dscuentas->data["total"][$li_i],2,",",".");
					$ls_codest1=substr($ls_codpro,0,25);
					$ls_codest1=substr($ls_codest1,$li_longestpro1-1,$li_loncodestpro1);
					$ls_codest2=substr($ls_codpro,25,25);
					$ls_codest2=substr($ls_codest2,$li_longestpro2-1,$li_loncodestpro2);
					$ls_codest3=substr($ls_codpro,50,25);
					$ls_codest3=substr($ls_codest3,$li_longestpro3-1,$li_loncodestpro3);
					$ls_codest4=substr($ls_codpro,75,25);
					$ls_codest4=substr($ls_codest4,$li_longestpro4-1,$li_loncodestpro4);
					$ls_codest5=substr($ls_codpro,100,25);
					$ls_codest5=substr($ls_codest5,$li_longestpro5-1,$li_loncodestpro5);
					$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
					$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>"."<input name=txtestclagas".$li_fila."       type=hidden size='2' id=txtestclagas".$li_fila."  value='".$ls_estcla."'>";
					$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
					$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
					$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
											"<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_programatica."'>";
				}
			}
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='' readonly>"."<input name=txtestclagas".$li_fila."       type=hidden size='2' id=txtestclagas".$li_fila."  value=''>";
		$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value=''>";        

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_solicitud);
	}// end function uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_cargo($as_numsol,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca las cuentas asociadas a los cargos de una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo;
		global $li_longestpro1,$li_longestpro2,$li_longestpro3,$li_longestpro4,$li_longestpro5;
		global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
		// Titulos el Grid
		$lo_title[1]="Cargo";
		$lo_title[2]="Estructura Programatica";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		$lo_title[5]=""; 
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$li_len1=20;
				$li_len2=6;
				$li_len3=3;
				$li_len4=2;
				$li_len5=2;
				break;
				
			case "2": // Modalidad por Presupuesto
				$li_len1=2;
				$li_len2=2;
				$li_len3=2;
				$li_len4=2;
				$li_len5=2;
				break;
		}
		require_once("sigesp_sep_c_solicitud.php");
		$io_solicitud=new sigesp_sep_c_solicitud("../../");
		$rs_data = $io_solicitud->uf_load_cuentas_cargo($as_numsol);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codcargo=$row["codcar"];
			$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_cuenta=$row["spg_cuenta"];
			$ls_estcla=$row["estcla"];
			$li_moncue=number_format($row["total"],2,",",".");
			$ls_codest1=substr($ls_codpro,0,25);
			$ls_codest1=substr($ls_codest1,$li_longestpro1-1,$li_loncodestpro1);
			$ls_codest2=substr($ls_codpro,25,25);
			$ls_codest2=substr($ls_codest2,$li_longestpro2-1,$li_loncodestpro2);
			$ls_codest3=substr($ls_codpro,50,25);
			$ls_codest3=substr($ls_codest3,$li_longestpro3-1,$li_loncodestpro3);
			$ls_codest4=substr($ls_codpro,75,25);
			$ls_codest4=substr($ls_codest4,$li_longestpro4-1,$li_loncodestpro4);
			$ls_codest5=substr($ls_codpro,100,25);
			$ls_codest5=substr($ls_codest5,$li_longestpro5-1,$li_loncodestpro5);
			$ls_programatica=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$ls_codcargo."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>".
									"<input name=txtestclacar".$li_fila."       type=hidden size='2' id=txtestclacar".$li_fila."  value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
			$lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
									"<input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value='".$ls_estcla."'>".
									"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_programatica."'>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=45 value='' readonly>"."<input name=txtestclacar".$li_fila."       type=hidden size='2' id=txtestclacar".$li_fila."  value=''>";
		$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value=''>";        

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas Cargos","gridcuentascargos");
		unset($io_solicitud);
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cierrecuentas_gasto($ai_total,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cierrecuentas_gasto
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
			   $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();		
		// Titulos el Grid
		$lo_title[1]="Estructura Programatica";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias
		switch ($as_tipo)
		{
			case "B":
				for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
				{ 
					$li_moncue= $io_funciones_sep->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
					$ls_cuenta= trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,""));
					$ls_codprogas= trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
					$ls_estclapre= trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
					$li_moncue= str_replace(".","",$li_moncue);
					$li_moncue= str_replace(",",".",$li_moncue);	
					$ls_codestpro1= substr($ls_codprogas,0,25);
					$ls_codestpro2= substr($ls_codprogas,25,25);
					$ls_codestpro3= substr($ls_codprogas,50,25); 
					$ls_codestpro4= substr($ls_codprogas,75,25);
					$ls_codestpro5= substr($ls_codprogas,100,25); 					
					if (!empty($ls_cuenta))
					{
						$io_dscuentas->insertRow("estclagas",$ls_estclapre);
						$io_dscuentas->insertRow("codprogas",$ls_codprogas);
						$io_dscuentas->insertRow("cuentagas",$ls_cuenta);			
						$io_dscuentas->insertRow("moncuegas",$li_moncue);
						$io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
						$io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
						$io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
						$io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
						$io_dscuentas->insertRow("codestpro5",$ls_codestpro5);			
					}
				}
			break;
			
			case "S":
				for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
				{ 
					$li_moncue= $io_funciones_sep->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
					$ls_cuenta= trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,""));
					$ls_codprogas= trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
					$ls_estclapre= trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
					$li_moncue= str_replace(".","",$li_moncue);
					$li_moncue= str_replace(",",".",$li_moncue);	
					$ls_codestpro1= substr($ls_codprogas,0,25);
					$ls_codestpro2= substr($ls_codprogas,25,25);
					$ls_codestpro3= substr($ls_codprogas,50,25); 
					$ls_codestpro4= substr($ls_codprogas,75,25);
					$ls_codestpro5= substr($ls_codprogas,100,25); 					
					if (!empty($ls_cuenta))
					{
						$io_dscuentas->insertRow("estclagas",$ls_estclapre);
						$io_dscuentas->insertRow("codprogas",$ls_codprogas);
						$io_dscuentas->insertRow("cuentagas",$ls_cuenta);			
						$io_dscuentas->insertRow("moncuegas",$li_moncue);
						$io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
						$io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
						$io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
						$io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
						$io_dscuentas->insertRow("codestpro5",$ls_codestpro5);			
					}
				}
			
			break;
			case "O":
				for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
				{ 
					$li_moncue= $io_funciones_sep->uf_obtenervalor("txtsubtotcon".$li_fila,"0,00");
					$ls_cuenta= trim($io_funciones_sep->uf_obtenervalor("txtcodspg".$li_fila,""));
					$ls_codprogas= trim($io_funciones_sep->uf_obtenervalor("txtcodgas".$li_fila,""));
					$ls_estclapre= trim($io_funciones_sep->uf_obtenervalor("txtstatus".$li_fila,""));
					$li_moncue= str_replace(".","",$li_moncue);
					$li_moncue= str_replace(",",".",$li_moncue);	
					$ls_codestpro1= substr($ls_codprogas,0,25);
					$ls_codestpro2= substr($ls_codprogas,25,25);
					$ls_codestpro3= substr($ls_codprogas,50,25); 
					$ls_codestpro4= substr($ls_codprogas,75,25);
					$ls_codestpro5= substr($ls_codprogas,100,25); 					
					if (!empty($ls_cuenta))
					{
						$io_dscuentas->insertRow("estclagas",$ls_estclapre);
						$io_dscuentas->insertRow("codprogas",$ls_codprogas);
						$io_dscuentas->insertRow("cuentagas",$ls_cuenta);			
						$io_dscuentas->insertRow("moncuegas",$li_moncue);
						$io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
						$io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
						$io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
						$io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
						$io_dscuentas->insertRow("codestpro5",$ls_codestpro5);			
					}
				}
			
			break;
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4','4'=>'codestpro5','5'=>'estclagas','6'=>'cuentagas'),array('0'=>'moncuegas'),'moncuegas');
		$li_total=$io_dscuentas->getRowCount('codprogas');
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codprogas=$io_dscuentas->getValue('codprogas',$li_fila);
			$ls_cuenta=$io_dscuentas->getValue('cuentagas',$li_fila);
			$ls_estcla=$io_dscuentas->getValue('estclagas',$li_fila);
			$li_moncue=number_format($io_dscuentas->getValue('moncuegas',$li_fila),2,",",".");
			$ls_codestpro1 = substr($ls_codprogas,0,25);
			$ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
			$ls_codestpro2 = substr($ls_codprogas,25,25);
			$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
			$ls_codestpro3 = substr($ls_codprogas,50,25);
			$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
			$ls_codestpro  = "";
			if (!empty($ls_codprogas))
			{
				$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
			} 
			if ($li_estmodest==2)
			{
				if (!empty($ls_codprogas))
				{
					$ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
					$ls_codestpro4 = substr($ls_codprogas,75,25);
					$ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					$ls_codestpro5 = substr($ls_codprogas,100,25);
					$ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
					$ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
				}							   
			}
			elseif($li_estmodest==1) 
			{
				if ($ls_estcla=='P')
				{
					$ls_denestcla = 'Proyecto';
				}
				elseif($ls_estcla=='A')
				{
					$ls_denestcla  = 'Actividad';
				} 
			} 
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_codestpro."' readonly>".
										"<input name=txtestclagas".$li_fila."  type=hidden size='2' id=txtestclagas".$li_fila."  value='".$ls_estcla."'>";
				$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >
										<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codprogas."'>";
				/*$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
										"<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_programatica."'>";*/
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtprogramaticagas".$ai_total." type=text id=txtprogramaticagas".$ai_total." class=sin-borde  style=text-align:center size=45 value='' readonly>"."<input name=txtestclagas".$li_fila."  type=hidden size='2' id=txtestclagas".$li_fila."  value=''>";;
		$lo_object[$ai_total][2]="<input name=txtcuentagas".$ai_total."       type=text id=txtcuentagas".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtmoncuegas".$ai_total."       type=text id=txtmoncuegas".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtcodprogas".$ai_total."       type=hidden id=txtcodprogas".$ai_total."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0' style='display:none'> ";
		print "    <tr>";
		print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_dscuentas);
	}// end function uf_print_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cierrecuentas_cargo($ai_total,$as_cargarcargos,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cierrecuentas_cargo
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_cargarcargos // Si cargamos los cargos ó solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep,$la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
	 	       $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Cr&eacute;dito";
		$lo_title[2]="Estructura Presupuestaria";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		//$lo_title[5]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias del Cargo
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{ 
			$ls_cargo= trim($io_funciones_sep->uf_obtenervalor("txtcodcar".$li_fila,""));
			$li_moncue= $io_funciones_sep->uf_obtenervalor("txtmoncar".$li_fila,""); 
			$ls_cuenta= trim($io_funciones_sep->uf_obtenervalor("txtcodspgcre".$li_fila,""));
		    $ls_codpro= $io_funciones_sep->uf_obtenervalor("txtcodgascre".$li_fila,"");
			$ls_estcla= $io_funciones_sep->uf_obtenervalor("txtstatuscre".$li_fila,"");
			$li_moncue = str_replace(".","",$li_moncue);
			$li_moncue = str_replace(",",".",$li_moncue);
		    $ls_codestpro1 = substr($ls_codpro,0,25); 
			$ls_codestpro2 = substr($ls_codpro,25,25); 
			$ls_codestpro3 = substr($ls_codpro,50,25); 
			$ls_codestpro4 = substr($ls_codpro,75,25); 
			$ls_codestpro5 = substr($ls_codpro,100,25);	
			if($ls_cuenta!="")
			{
				$valores["codcargo"]=$ls_cargo;
				$valores["cuentacar"]=$ls_cuenta;
				$valores["estcla"]=$ls_estcla;
				$valores["codestpro1"]=$ls_codestpro1;
				$valores["codestpro2"]=$ls_codestpro2;
				$valores["codestpro3"]=$ls_codestpro3;
				$valores["codestpro4"]=$ls_codestpro4;
				$valores["codestpro5"]=$ls_codestpro5;
				$ll_row_found=$io_dscuentas->findValues($valores,"codcargo") ;
				if($ll_row_found>0)
				{  
					$ldec_monto=0;
					$ldec_monto=$io_dscuentas->getValue("moncuecar",$ll_row_found);
					$ldec_monto=$ldec_monto + $li_moncue;
					$io_dscuentas->updateRow("moncuecar",$ldec_monto,$ll_row_found);	
				}
				else
				{
					$io_dscuentas->insertRow("codcargo",$ls_cargo);			
					$io_dscuentas->insertRow("codprocar",$ls_codpro);			
					$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
					$io_dscuentas->insertRow("moncuecar",$li_moncue);
					$io_dscuentas->insertRow("estcla",$ls_estcla);
					$io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
					$io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
					$io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
					$io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
					$io_dscuentas->insertRow("codestpro5",$ls_codestpro5);	
				}		
			}
		}
		if($as_cargarcargos=="1")
		{	// si los cargos se deben cargar recorremos el arreglo de cuentas
			// que se lleno con los cargos 
			$li_cuenta=count($la_cuentacargo)-1;
			for($li_fila=1;($li_fila<=$li_cuenta);$li_fila++)
			{
				$ls_cargo        = trim($la_cuentacargo[$li_fila]["cargo"]);
				$ls_cuenta       = trim($la_cuentacargo[$li_fila]["cuenta"]);
				$ls_programatica = trim($la_cuentacargo[$li_fila]["programatica"]);
				$ls_estcla       = trim($la_cuentacargo[$li_fila]["estcla"]);
				$li_moncue="0.00";
				$ls_codestpro1 = substr($ls_programatica,0,25);
			    $ls_codestpro2 = substr($ls_programatica,25,25);
			    $ls_codestpro3 = substr($ls_programatica,50,25);
			    $ls_codestpro4 = substr($ls_programatica,75,25);
			    $ls_codestpro5 = substr($ls_programatica,100,25); 
				if($ls_cuenta!="")
				{
					$valores["codcargo"]=$ls_cargo;
					$valores["cuentacar"]=$ls_cuenta;
					$valores["estcla"]=$ls_estcla;
					$valores["codestpro1"]=$ls_codestpro1;
					$valores["codestpro2"]=$ls_codestpro2;
					$valores["codestpro3"]=$ls_codestpro3;
					$valores["codestpro4"]=$ls_codestpro4;
					$valores["codestpro5"]=$ls_codestpro5;
					$ll_row_found=$io_dscuentas->findValues($valores,"codcargo") ;
					if($ll_row_found>0)
					{  
						$ldec_monto=0;
						$ldec_monto=$io_dscuentas->getValue("moncuecar",$ll_row_found);
						$ldec_monto=$ldec_monto + $li_moncue;
						$io_dscuentas->updateRow("moncuecar",$ldec_monto,$ll_row_found);	
					}
					else
					{
						$io_dscuentas->insertRow("codcargo",$ls_cargo);			
						$io_dscuentas->insertRow("codprocar",$ls_programatica);			
						$io_dscuentas->insertRow("cuentacar",$ls_cuenta);			
						$io_dscuentas->insertRow("moncuecar",$li_moncue);
						$io_dscuentas->insertRow("estcla",$ls_estcla);
						$io_dscuentas->insertRow("codestpro1",$ls_codestpro1);
						$io_dscuentas->insertRow("codestpro2",$ls_codestpro2);
						$io_dscuentas->insertRow("codestpro3",$ls_codestpro3);
						$io_dscuentas->insertRow("codestpro4",$ls_codestpro4);
						$io_dscuentas->insertRow("codestpro5",$ls_codestpro5);
					}
				}			
			}
		} 
		// Agrupamos las cuentas por programatica y cuenta
		//$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codprocar','2'=>'cuentacar','3'=>'estcla'),array('0'=>'moncuecar'),'moncuecar');
		$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codestpro1','2'=>'codestpro2','3'=>'codestpro3','4'=>'codestpro4','5'=>'codestpro5',
		                              '6'=>'estcla','7'=>'cuentacar'),array('0'=>'moncuecar'),'moncuecar');
		$li_total=$io_dscuentas->getRowCount('codcargo');	
		//print_r($io_dscuentas->data);
		// Recorremos el data stored de cuentas que se lleno y se agrupo anteriormente
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{ 
			$ls_cargo     = $io_dscuentas->getValue('codcargo',$li_fila);
			$ls_codpro    = $io_dscuentas->getValue('codprocar',$li_fila);
			$ls_cuenta    = $io_dscuentas->getValue('cuentacar',$li_fila);
			$li_moncue    = number_format($io_dscuentas->getValue('moncuecar',$li_fila),2,",",".");
			$ls_codestpro = "";
			if (!empty($ls_codpro))
			   {
				 $ls_codestpro1 = substr($ls_codpro,0,25);
				 $ls_codestpro2 = substr($ls_codpro,25,25);
				 $ls_codestpro3 = substr($ls_codpro,50,25);
				 $ls_codestpro4 = substr($ls_codpro,75,25);
				 $ls_codestpro5 = substr($ls_codpro,100,25);
				 $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
			 	 $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
				 $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
				 $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
			   } 
			if ($li_estmodest==2)
			   {
			     if (!empty($ls_codpro))
				    {
					  $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
					  $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
					  $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
					}
			   }
			$ls_estcla = $io_dscuentas->getValue('estcla',$li_fila);
			if($ls_cuenta!="")
			{

				$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=12 value='".$ls_cargo."' readonly>";
				$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=75 value='".$ls_codestpro."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' readonly>".
				                        "<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codpro."'>".
										"<input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value='".$ls_estcla."'>";
			   // $lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
										
			}
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtcodcargo".$ai_total." type=text id=txtcodcargo".$ai_total." class=sin-borde  style=text-align:center size=12 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtprogramaticacar".$ai_total." type=text id=txtprogramaticacar".$ai_total." class=sin-borde  style=text-align:center size=75 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtcuentacar".$ai_total."       type=text id=txtcuentacar".$ai_total."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtmoncuecar".$ai_total."       type=text id=txtmoncuecar".$ai_total."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_total][5]="<input name=txtcodprocar".$ai_total."       type=hidden id=txtcodprocar".$ai_total."  value=''><input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Otros Cr&eacute;ditos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas Otros Cr&eacute;ditos","gridcuentascargos");
		unset($io_dscuentas);
	}// end function uf_print_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
?>