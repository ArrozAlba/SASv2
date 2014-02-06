<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	
	$io_conexiones->decodificar_post();

	// tipo de la orden de compra si es de BIENES ó de SERVICIOS
	$ls_tipo=$io_funciones_soc->uf_obtenervalor("tipo","");
	// tipo de la solicitud si es orden de compra  o es una sep
	$ls_tipsol=$io_funciones_soc->uf_obtenervalor("tipsol","");
	// proceso a ejecutar
    $ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	// total de filas de Bienes
	$li_totalbienes=$io_funciones_soc->uf_obtenervalor("totalbienes","1");
	// total de filas de Servicios
	$li_totalservicios=$io_funciones_soc->uf_obtenervalor("totalservicios","1");
	// total de filas de Cargos
	$li_totalcargos=$io_funciones_soc->uf_obtenervalor("totalcargos","1");
	// total de filas de Cuentas
	$li_totalcuentas=$io_funciones_soc->uf_obtenervalor("totalcuentas","1");
	// total de filas de Cuentas cargos
	$li_totalcuentascargo=$io_funciones_soc->uf_obtenervalor("totalcuentascargo","1");
	// Indica si se deben cargar los cargos de un bien ó servicios ó si solo se deben pintar
	$ls_cargarcargos=$io_funciones_soc->uf_obtenervalor("cargarcargos","1");
	// Valor del Subtotal de la orden de compra
	$li_subtotal=$io_funciones_soc->uf_obtenervalor("subtotal","0,00");
	// Valor del Cargo de la orden de compra
	$li_cargos=$io_funciones_soc->uf_obtenervalor("cargos","0,00");
	// Valor del Total de la orden de compra
	$li_total=$io_funciones_soc->uf_obtenervalor("total","0,00");
	// Número de orden de compra si se va a cargar
	$ls_numsol=$io_funciones_soc->uf_obtenervalor("numsol","");
	// Número de orden de compra si se va a cargar
	$ls_numordcom=$io_funciones_soc->uf_obtenervalor("numordcom","");
	// tipo de contribuyente del proveedor de la orden de compra 
	$ls_tipconpro = $io_funciones_soc->uf_obtenervalor("tipconpro","");
    $ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];//Tipo de la Afectación del IVA, P=Presupuesto y C=Contabilidad.
	$ls_disabled  = ""; 
	$li_estciespg = $_SESSION["la_empresa"]["estciespg"];
	$li_estciespi = $_SESSION["la_empresa"]["estciespi"];
	if ($li_estciespg==1 || $li_estciespi==1)
	   {
	     $ls_disabled = "disabled";
	   }

	global $li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5,$ls_disabled;
	$li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
	$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	
	$ls_titulo="";
	$la_cuentacargo[0]="";
	$li_cuenta=1;
	switch($ls_proceso)
	{
		case "CARGAR-COMBO":
			switch($ls_tipo)
			{
				case "ESTADOS": 
					require_once("sigesp_soc_c_registro_orden_compra.php");
					$io_registro_ordcom=new sigesp_soc_c_registro_orden_compra("../../");
	                $ls_codpai=$io_funciones_soc->uf_obtenervalor("cmbpais","-");
	                $ls_seleccionado=$io_funciones_soc->uf_obtenervalor("despai","");
					$io_registro_ordcom->uf_soc_combo_estado($ls_seleccionado,$ls_codpai);
					unset($io_registro_ordcom);
				break;
					
				case "MUNICIPIOS": 
					require_once("sigesp_soc_c_registro_orden_compra.php");
					$io_registro_ordcom=new sigesp_soc_c_registro_orden_compra("../../");
	                $ls_codpai=$io_funciones_soc->uf_obtenervalor("cmbpais","-");
	                $ls_codest=$io_funciones_soc->uf_obtenervalor("cmbestado","-");
	                $ls_seleccionado=$io_funciones_soc->uf_obtenervalor("desest","");
					$io_registro_ordcom->uf_soc_combo_municipio($ls_seleccionado,$ls_codpai,$ls_codest);
					unset($io_registro_ordcom);
				break;
					
				case "PARROQUIAS": 
					require_once("sigesp_soc_c_registro_orden_compra.php");
					$io_registro_ordcom=new sigesp_soc_c_registro_orden_compra("../../");
	                $ls_codpai=$io_funciones_soc->uf_obtenervalor("cmbpais","-");
	                $ls_codest=$io_funciones_soc->uf_obtenervalor("cmbestado","-");
	                $ls_codmun=$io_funciones_soc->uf_obtenervalor("cmbmunicipio","-");
	                $ls_seleccionado=$io_funciones_soc->uf_obtenervalor("denmun","");
					$io_registro_ordcom->uf_soc_combo_parroquia($ls_seleccionado,$ls_codpai,$ls_codest,$ls_codmun);
					unset($io_registro_ordcom);
				break;
			}
		break;
		case "LIMPIAR":
			switch($ls_tipo)
			{
				case "B": // Bienes
					$ls_titulo="Bien o Material";
					uf_print_bienes($li_totalbienes,$ls_tipconpro,$ls_tipsol);
					uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"B",$ls_tipconpro,$ls_tipsol);
				break;
					
				case "S": // Servicios
					$ls_titulo="Servicios";
					uf_print_servicios($li_totalservicios,$ls_tipconpro,$ls_tipsol);
					uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"S",$ls_tipconpro,$ls_tipsol);
				break;
			}
		break;
			
		case "LOADBIENES":
			$ls_titulo="Bien o Material";
			uf_load_bienes($ls_numordcom,$ls_tipsol);
			uf_load_creditos($ls_titulo,$ls_numordcom,"B",$ls_tipsol,"B");
			uf_load_cuentas($ls_numordcom,"B",$ls_tipsol); 
			if ($ls_tipafeiva=='P')
			   {
			      uf_load_cuentas_cargo($ls_numordcom,"B",$ls_tipsol);
			   }
			uf_load_total($li_subtotal,$li_cargos,$li_total);
		break;
		
		case "LOADSERVICIOS":
			$ls_titulo="Servicios";
			uf_load_servicios($ls_numordcom,$ls_tipsol);
			uf_load_creditos($ls_titulo,$ls_numordcom,"S",$ls_tipsol,"S");
			uf_load_cuentas($ls_numordcom,"S",$ls_tipsol);
			if ($ls_tipafeiva=='P')
			   {
			      uf_load_cuentas_cargo($ls_numordcom,"S",$ls_tipsol);
			   }
			uf_load_total($li_subtotal,$li_cargos,$li_total);
		break;
		
		case "AGREGARBIENES":
			$ls_titulo="Bien o Material";
			uf_print_bienes($li_totalbienes,$ls_tipconpro,$ls_tipsol);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"B",$ls_tipconpro,$ls_tipsol);
		break;
		
		case "AGREGARSERVICIOS":
			$ls_titulo="Servicios";
			uf_print_servicios($li_totalservicios,$ls_tipconpro,$ls_tipsol);
			uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"S",$ls_tipconpro,$ls_tipsol);
		break;
		
		case "AGREGARBIENES-SEP":
			$ls_titulo="Bien o Material";
			$ld_subordcom=$ld_creordcom=0;
			uf_print_bienes_sep($li_totalbienes,$ls_numsol,$ls_tipsol,$ls_tipconpro,$ld_subordcom,$ld_creordcom);
			uf_print_creditos_sep($ls_titulo,$li_totalcargos,$ls_cargarcargos,"B",$ls_numsol,$ls_tipsol,$ls_tipconpro,$ls_numordcom);
		break;
		
		case "AGREGARSERVICIOS-SEP":
			$ls_titulo="Servicios";
		    $ld_subordcom=$ld_creordcom=0;
			uf_print_servicios_sep($li_totalservicios,$ls_numsol,$ls_tipsol,$ls_tipconpro,$ld_subordcom,$ld_creordcom);
			uf_print_creditos_sep($ls_titulo,$li_totalcargos,$ls_cargarcargos,"S",$ls_numsol,$ls_tipsol,$ls_tipconpro,$ls_numordcom);
		break;
		case "AGREGARCUENTAS":
			if($ls_tipo=="B")
			{
				$ls_titulo="Bien o Material";
				uf_print_bienes($li_totalbienes,$ls_tipconpro,$ls_tipsol);
				uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"B",$ls_tipconpro,$ls_tipsol);
				uf_print_cierrecuentas_gasto($li_totalbienes,"B");
				if ($ls_tipafeiva=='P')
				{
					uf_print_cierrecuentas_cargo($li_totalcargos,$ls_cargarcargos,"B",$ls_tipconpro);
				}
				uf_print_total($li_totalbienes,$li_totalcargos,"B");
			}
			else
			{
				$ls_titulo="Servicios";
				uf_print_servicios($li_totalservicios,$ls_tipconpro,$ls_tipsol);
				uf_print_creditos($ls_titulo,$li_totalcargos,$ls_cargarcargos,"S",$ls_tipconpro,$ls_tipsol);
				uf_print_cierrecuentas_gasto($li_totalservicios,"S");
				if ($ls_tipafeiva=='P')
				{
					uf_print_cierrecuentas_cargo($li_totalcargos,$ls_cargarcargos,"S",$ls_tipconpro);
				}
				uf_print_total($li_totalservicios,$li_totalcargos,"S");
			}
		break;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes($ai_totalbienes,$as_tipconpro,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: ai_totalbienes  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los Bienes
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad";
		$lo_title[5]="Precio/Unid.";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cr&eacute;ditos"; 
		$lo_title[8]="Total";
		$lo_title[9]=""; 
		// Recorrido de todos los Bienes del Grid
	    require_once("sigesp_soc_c_registro_orden_compra.php");
	    $io_registro=new sigesp_soc_c_registro_orden_compra("../../");
		$ls_estmodpart=$io_registro->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidasç
		//$ls_mostrarcambio=$io_registro->uf_buscar_estatus_orden();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
		  if($as_tipsol=="SOC")
		   {
			   $lo_title[10]="";
		   }
		}
		for($li_fila=1;$li_fila<=$ai_totalbienes;$li_fila++)
		{
			$ls_codart       = trim($io_funciones_soc->uf_obtenervalor("txtcodart".$li_fila,""));
			$ls_denart       = $io_funciones_soc->uf_obtenervalor("txtdenart".$li_fila,"");
			$li_canart       = $io_funciones_soc->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			$ls_unidad       = $io_funciones_soc->uf_obtenervalor("cmbunidad".$li_fila,"M");
			$li_preart       = $io_funciones_soc->uf_obtenervalor("txtpreart".$li_fila,"0,00");
			$li_subtotart    = $io_funciones_soc->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
            $li_carart       = $io_funciones_soc->uf_obtenervalor("txtcarart".$li_fila,"0,00");
			$li_totart       = $io_funciones_soc->uf_obtenervalor("txttotart".$li_fila,"0,00");
			$ls_spgcuenta    = trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
			$li_unidad       = $io_funciones_soc->uf_obtenervalor("txtunidad".$li_fila,"");
			$ls_numsolord    = trim($io_funciones_soc->uf_obtenervalor("txtnumsolord".$li_fila,""));
			$ls_coduniadmsep = trim($io_funciones_soc->uf_obtenervalor("txtcoduniadmsep".$li_fila,""));
			$ls_denuniadmsep = trim($io_funciones_soc->uf_obtenervalor("txtdenuniadmsep".$li_fila,""));
			$ls_codestpro    = trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));
			$ls_estcla       = trim($io_funciones_soc->uf_obtenervalor("estcla".$li_fila,""));
		    ///---------campos relacionados al gasto----------------------------------------
//			$ls_codprogrid=trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$li_fila,"")); 
//			$ls_cuentagrid=trim($io_funciones_soc->uf_obtenervalor("txtcuentagas".$li_fila,""));
//			$ls_estclagrid=trim($io_funciones_soc->uf_obtenervalor("estclapre".$li_fila,""));
			//-------------------------------------------------------------------------------	
		
			if($ls_numsolord=="")
			{
			  $ls_numsolord = '---------------';
			} 
			
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
 			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."       id=txtcodart".$li_fila."    class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>".
			                         "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									 "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                         "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                         "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>".
									 "<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."       id=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly>".
			                        "<input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'>".
									"<input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'>".
									"<input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."       id=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila."  style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."       id=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila."    id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."       id=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."       id=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila." value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila."    value='".$li_unidad."'>";
			if($li_fila==$ai_totalbienes)// si es la ú	ltima fila no pinto el eliminar
			{
				$lo_object[$li_fila][9]="";
				if ($ls_estmodpart==1)
				{ 
				  if($as_tipsol=="SOC")
					 {
					     $lo_object[$li_fila][10]=" ";
					 }
				}
			}
			else
			{ 
				$lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			   	if ($ls_estmodpart==1)
					{ 
					  if($as_tipsol=="SOC")
					  {
						$lo_object[$li_fila][10]="<a href=javascript:ue_cambiar_partida_bien('".$li_fila."','".$ls_codestpro."','".$ls_spgcuenta."','".$ls_estcla."','1');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
					  }
					}
			}

		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogo_bienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_totalbienes,$lo_title,$lo_object,840,"Detalle de Bienes","gridbienes");
	}// end function uf_print_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios($ai_total,$as_tipconpro,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los servicios
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007					Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cr&eacute;ditos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		require_once("sigesp_soc_c_registro_orden_compra.php");
	    $io_registro=new sigesp_soc_c_registro_orden_compra("../../");
		$ls_estmodpart=$io_registro->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{  
		   if($as_tipsol=="SOC")
			{
			   $lo_title[9]="";
			}
		}
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codser		 = $io_funciones_soc->uf_obtenervalor("txtcodser".$li_fila,"");
			$ls_denser		 = $io_funciones_soc->uf_obtenervalor("txtdenser".$li_fila,"");
			$li_canser		 = $io_funciones_soc->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			$li_preser		 = $io_funciones_soc->uf_obtenervalor("txtpreser".$li_fila,"0,00");
			$li_subtotser    = $io_funciones_soc->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
			$li_carser       = $io_funciones_soc->uf_obtenervalor("txtcarser".$li_fila,"0,00");
			$li_totser       = $io_funciones_soc->uf_obtenervalor("txttotser".$li_fila,"0,00");
			$ls_spgcuenta    = trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
			$ls_numsolord    = $io_funciones_soc->uf_obtenervalor("txtnumsolord".$li_fila,"");
			$ls_coduniadmsep = $io_funciones_soc->uf_obtenervalor("txtcoduniadmsep".$li_fila,"");
			$ls_denuniadmsep = $io_funciones_soc->uf_obtenervalor("txtdenuniadmsep".$li_fila,"");
			$ls_codestpro    = trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));
			$ls_estcla       = trim($io_funciones_soc->uf_obtenervalor("estcla".$li_fila,""));
			///---------campos relacionados al gasto----------------------------------------
//			$ls_codproser=trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$li_fila,""));
//			$ls_cuentaser=trim($io_funciones_soc->uf_obtenervalor("txtcuentagas".$li_fila,"")); 
//			$ls_estclaser=trim($io_funciones_soc->uf_obtenervalor("estclapre".$li_fila,""));
			//-------------------------------------------------------------------------------	

            if($ls_numsolord=="")
			{
			  $ls_numsolord = '---------------';
			} 
			
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>".
			                        "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									"<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>".
						            "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                        "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                        "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>".
			                        "<input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'>".
			                        "<input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'>".
			                        "<input type=hidden name=estcla".$li_fila."       id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'>";

			if($li_fila==$ai_total)// si es la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][8]="";
				if ($ls_estmodpart==1)
				{ 
				  if($as_tipsol=="SOC")
				    {
					   $lo_object[$li_fila][9]=" ";
				    }
				}
			}
			else
			{
				$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
			    if ($ls_estmodpart==1)
					{ 
					  if($as_tipsol=="SOC")
					  {
						$lo_object[$li_fila][9]="<a href=javascript:ue_cambiar_partida_servicio('".$li_fila."','".$ls_codestpro."','".$ls_spgcuenta."','".$ls_estcla."','3');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
					  }
					}
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogo_servicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos($as_titulo,$ai_total,$as_cargarcargos,$as_tipo,$as_tipconpro,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_cargarcargos // Si cargamos los cargos ó solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de créditos y busca los creditos de un Bien, un Servicio ò un concepto
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo, $li_cuenta;

		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto Otros Cr&eacute;ditos";
		$lo_title[6]="Sub-Total";
		$lo_object[0]="";
		$ai_total2=0;
		require_once("sigesp_soc_c_registro_orden_compra.php");
	    $io_registro=new sigesp_soc_c_registro_orden_compra("../../");
		$ls_estmodpart=$io_registro->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
		   if($as_tipsol=="SOC")
		    {
				$lo_title[7]=""; 
		    }
		}
		// Recorrido de el grid de Cargos
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{  
			$ls_codservic  = trim($io_funciones_soc->uf_obtenervalor("txtcodservic".$li_fila,""));
			$ls_codcar     = trim($io_funciones_soc->uf_obtenervalor("txtcodcar".$li_fila,""));
			$ls_dencar     = $io_funciones_soc->uf_obtenervalor("txtdencar".$li_fila,"");
			$li_bascar     = $io_funciones_soc->uf_obtenervalor("txtbascar".$li_fila,"");
			$li_moncar     = $io_funciones_soc->uf_obtenervalor("txtmoncar".$li_fila,""); 
			$li_subcargo   = $io_funciones_soc->uf_obtenervalor("txtsubcargo".$li_fila,"");
			$ls_spg_cuenta = trim($io_funciones_soc->uf_obtenervalor("cuentacargo".$li_fila,""));
			$ls_formula    = $io_funciones_soc->uf_obtenervalor("formulacargo".$li_fila,"");
			$ls_numsep     = $io_funciones_soc->uf_obtenervalor("hidnumsepcar".$li_fila,"");
		    $ls_codprog     = $io_funciones_soc->uf_obtenervalor("codprogcargo".$li_fila,"");
			$ls_estcla    = $io_funciones_soc->uf_obtenervalor("estclacargo".$li_fila,""); 
			///---------campos relacionados al gasto----------------------------------------
//			$ls_codpro=trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$li_fila,""));
//			$ls_cuenta=trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
//			$ls_estclapre=trim($io_funciones_soc->uf_obtenervalor("estclapre".$li_fila,""));
			//-------------------------------------------------------------------------------
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>".
			                        "<input name=hidnumsepcar".$li_fila."  type=hidden id=hidnumsepcar".$li_fila." value='".$ls_numsep."'>".
									"<input type=hidden name=txtcodgascre".$li_fila." id=txtcodgascre".$li_fila."  value='".$ls_codprog."' readonly>".
			                        "<input type=hidden name=txtcodspgcre".$li_fila." id=txtcodspgcre".$li_fila."  value='".$ls_spg_cuenta."' readonly>".
			                        "<input type=hidden name=txtstatuscre".$li_fila." id=txtstatuscre".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									"<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
								    "<input name=codprogcargo".$li_fila."  type=hidden id=codprogcargo".$li_fila."  value='".$ls_codprog."'>".
									"<input type=hidden name=estclacargo".$li_fila." id=estclacargo".$li_fila." value='".$ls_estcla."'>".	
									"<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
			//$lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		    if ($ls_estmodpart==1)
			{
			  if($as_tipsol=="SOC")
			   { 
				  $lo_object[$li_fila][7]="<a href=javascript:ue_cambiar_creditos('".$li_fila."','".$ls_codprog."','".$ls_spg_cuenta."','".$ls_estcla."','2');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
			   }
			}
		}
		if($as_cargarcargos=="1")
		{	// Si se deben cargar los cargos Buscamos el Código del último Bien cargado 
			// y obtenemos los cargos de dicho Bien
		  if(($as_tipconpro=="O")||($as_tipconpro=="E"))
		  {  
			require_once("sigesp_soc_c_registro_orden_compra.php");
		    $io_registro_orden = new sigesp_soc_c_registro_orden_compra("../../");
			$ls_codigo         = $io_funciones_soc->uf_obtenervalor("txtcodservic","");
			$ls_codprounidad   = $io_funciones_soc->uf_obtenervalor("codprounidad","");
			$ls_estcla   = $io_funciones_soc->uf_obtenervalor("estcla",""); 
			switch ($as_tipo)
			{
				case "B":
					$rs_data = $io_registro_orden->uf_load_cargosbienes($ls_codigo,$ls_codprounidad,$ls_estcla);
					break;
				case "S":
					$rs_data = $io_registro_orden->uf_load_cargosservicios($ls_codigo,$ls_codprounidad,$ls_estcla);
					break;
			}
			while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
			{ 
				$lb_existecargo  = true;
				$ls_codservic    = trim($row["codigo"]);
				$ls_codcar       = trim($row["codcar"]);
				$ls_dencar       = $row["dencar"];
				$ls_spg_cuenta   = trim($row["spg_cuenta"]);
				$ls_formula      = $row["formula"];
				$li_bascar       = "0,00";
				$li_moncar       = "0,00";
				$li_subcargo     = "0,00";
				$ls_numsep       = '---------------';
				$ls_existecuenta = $row["existecuenta"]; 
				if ($ls_spg_cuenta!="")
				   {// Si la cuenta presupuestaria es diferente de blanco llenamos un arreglo de cuentas
					 $la_cuentacargo[$li_cuenta]["cargo"]=$ls_codcar;
					 $la_cuentacargo[$li_cuenta]["cuenta"]=$ls_spg_cuenta;
					 if ($ls_existecuenta==0)
					    {
						  $la_cuentacargo[$li_cuenta]["programatica"]="";
					      $la_cuentacargo[$li_cuenta]["estcla"]="";
					    }
					 else
					    {
						  $la_cuentacargo[$li_cuenta]["programatica"]=$ls_codprounidad;
						  $la_cuentacargo[$li_cuenta]["estcla"]= $ls_estcla;
					    }
					$li_cuenta++;
				} 
				$ai_total++; 
				$ai_total2=$ai_total-1;
				///---------campos relacionados al gasto----------------------------------------
//			     $ls_codpro=trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$ai_total2,""));
//			     $ls_cuenta=trim($io_funciones_soc->uf_obtenervalor("txtcuentagas".$ai_total2,"")); 
//			     $ls_estclapre=trim($io_funciones_soc->uf_obtenervalor("estclapre".$ai_total2,"")); 
			    //-------------------------------------------------------------------------------
				
				$lo_object[$ai_total][1]="<input name=txtcodservic".$ai_total." type=text id=txtcodservic".$ai_total." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>".
				                         "<input name=hidnumsepcar".$ai_total."  type=hidden id=hidnumsepcar".$ai_total." value='".$ls_numsep."'>".
										 "<input type=hidden name=txtcodgascre".$ai_total." id=txtcodgascre".$ai_total."  value='".$ls_codprounidad."' readonly>".
			                             "<input type=hidden name=txtcodspgcre".$ai_total." id=txtcodspgcre".$ai_total."  value='".$ls_spg_cuenta."' readonly>".
			                             "<input type=hidden name=txtstatuscre".$ai_total." id=txtstatuscre".$ai_total."  value='".$ls_estcla."' readonly>";
				$lo_object[$ai_total][2]="<input name=txtcodcar".$ai_total."    type=text id=txtcodcar".$ai_total."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
				$lo_object[$ai_total][3]="<input name=txtdencar".$ai_total."    type=text id=txtdencar".$ai_total."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
				$lo_object[$ai_total][4]="<input name=txtbascar".$ai_total."    type=text id=txtbascar".$ai_total."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
				$lo_object[$ai_total][5]="<input name=txtmoncar".$ai_total."    type=text id=txtmoncar".$ai_total."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
				$lo_object[$ai_total][6]="<input name=txtsubcargo".$ai_total."  type=text id=txtsubcargo".$ai_total."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
										 "<input name=cuentacargo".$ai_total."  type=hidden id=cuentacargo".$ai_total."  value='".$ls_spg_cuenta."'>".
									     "<input name=codprogcargo".$ai_total."  type=hidden id=codprogcargo".$ai_total."  value='".$ls_codprounidad."'>".
										 "<input type=hidden name=estclacargo".$ai_total." id=estclacargo".$ai_total." value='".$ls_estcla."'>".	
										 "<input name=formulacargo".$ai_total." type=hidden id=formulacargo".$ai_total." value='".$ls_formula."'>";
				//$lo_object[$ai_total][7]="<a href=javascript:ue_delete_cargos('".$ai_total."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			    if ($ls_estmodpart==1)
            	{
				   if($as_tipsol=="SOC")
				   {
					 $lo_object[$ai_total][7]="<a href=javascript:ue_cambiar_creditos('".$li_fila."','".$ls_codprounidad."','".$ls_spg_cuenta."','".$ls_estcla."','2');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
				   }
				}
			}
		  }	 
		}		
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Otros Cr&eacute;ditos","gridcreditos");
		unset($io_registro_orden);		
		print "<table width='840' height='22' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "        <tr>";
		print "          <td width='175' height='22' align='right'><div align='left'><input name='btncrear' type='button' class='boton' id='btncerrar' value='Crear Asiento' onClick='javascript: ue_crear_asiento();'></div></td>";
		print "        </tr>";
		print "</table>";
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_gasto($ai_total,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_gasto
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid,$io_funciones_soc,$la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
			   $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos del Grid
		$lo_title[1]="Estructura Presupuestaria";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias
		for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		    { 
			  $ls_codprogas = trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$li_fila,"")); 
			  $ls_cuenta    = trim($io_funciones_soc->uf_obtenervalor("txtcuentagas".$li_fila,""));
			  $li_moncue    = trim($io_funciones_soc->uf_obtenervalor("txtmoncuegas".$li_fila,"0,00"));
			  $ls_estclapre = trim($io_funciones_soc->uf_obtenervalor("estclapre".$li_fila,"")); 
			  $li_moncue    = str_replace(".","",$li_moncue); 
			  $li_moncue    = str_replace(",",".",$li_moncue);	
			  $ls_codestpro1 = substr($ls_codprogas,0,25);
			  $ls_codestpro2 = substr($ls_codprogas,25,25);
			  $ls_codestpro3 = substr($ls_codprogas,50,25); 
			  $ls_codestpro4 = substr($ls_codprogas,75,25);
			  $ls_codestpro5 = substr($ls_codprogas,100,25); 					
			  if (!empty($ls_cuenta))
			     {
				   $io_dscuentas->insertRow("estcla",$ls_estclapre);
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
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4','4'=>'codestpro5',
                                      '5'=>'cuentagas','6'=>'estcla'),array('0'=>'moncuegas'),'moncuegas'); 
		$li_total=$io_dscuentas->getRowCount('codestpro1');	
		for ($li_fila=1;$li_fila<=$li_total;$li_fila++)
		    {
			  $ls_codprogas  = trim($io_dscuentas->getValue('codprogas',$li_fila));
			  $ls_cuenta     = trim($io_dscuentas->getValue('cuentagas',$li_fila));
			  $li_moncue     = number_format($io_dscuentas->getValue('moncuegas',$li_fila),2,",",".");
		      $ls_estcla     = $io_dscuentas->getValue('estcla',$li_fila);
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
			  if (!empty($ls_cuenta))
			     {
				   $lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=80 value='".$ls_codestpro."' readonly>";
				   $lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=30 value='".$ls_cuenta."' readonly>";
				   $lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=30 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' readonly>".
				                           "<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codprogas."'>".
										   "<input name=estclapre".$li_fila."  id=estclapre".$li_fila." type=hidden value='".$ls_estcla."'>";
				   //$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
				   						   
				   
				 }
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtprogramaticagas".$ai_total." type=text id=txtprogramaticagas".$ai_total." class=sin-borde  style=text-align:center size=80 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtcuentagas".$ai_total."       type=text id=txtcuentagas".$ai_total."       class=sin-borde  style=text-align:center size=30 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtmoncuegas".$ai_total."       type=text id=txtmoncuegas".$ai_total."       class=sin-borde  style=text-align:right  size=30 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtcodprogas".$ai_total."       type=hidden id=txtcodprogas".$ai_total."  value=''>".
		                         "<input name=estclapre".$ai_total."  id=estclapre".$ai_total." type=hidden value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg($li_total);'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_dscuentas);
	}// end function uf_print_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargo($ai_total,$as_cargarcargos,$as_tipo,$as_tipconpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_cargo
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//                 as_cargarcargos // Si cargamos los cargos ó solo pintamos
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc,$la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
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
			$ls_cargo  = trim($io_funciones_soc->uf_obtenervalor("txtcodcargo".$li_fila,"")); 
			$ls_codpro = trim($io_funciones_soc->uf_obtenervalor("txtcodprocar".$li_fila,"")); ; 
			$ls_cuenta = trim($io_funciones_soc->uf_obtenervalor("txtcuentacar".$li_fila,"")); 
			$li_moncue = trim($io_funciones_soc->uf_obtenervalor("txtmoncuecar".$li_fila,"0,00"));
			$ls_estcla = trim($io_funciones_soc->uf_obtenervalor("estclacar".$li_fila,""));
			$li_moncue = str_replace(".","",$li_moncue);
			$li_moncue = str_replace(",",".",$li_moncue);
		    $ls_codestpro1 = substr($ls_codpro,0,25); 
			$ls_codestpro2 = substr($ls_codpro,25,25); 
			$ls_codestpro3 = substr($ls_codpro,50,25); 
			$ls_codestpro4 = substr($ls_codpro,75,25); 
			$ls_codestpro5 = substr($ls_codpro,100,25);	
			if($ls_cuenta!="")
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
		// Agrupamos las cuentas por programatica y cuenta
		//$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codprocar','2'=>'cuentacar','3'=>'estcla'),array('0'=>'moncuecar'),'moncuecar');
//		print_r($io_dscuentas->data);
		$io_dscuentas->group_by(array('0'=>'codcargo','1'=>'codestpro1','2'=>'codestpro2','3'=>'codestpro3','4'=>'codestpro4','5'=>'codestpro5',
		                              '6'=>'cuentacar','7'=>'estcla'),array('0'=>'moncuecar'),'moncuecar');
		$li_total=$io_dscuentas->getRowCount('codcargo');	
//		print_r($io_dscuentas->data);
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totbieser,$ai_cargos,$as_tipo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal ---> valor del subtotal
		//				   ai_cargos ---> valor total de los cargos
		//				   ai_total ---> total de la solicitu de pago
		//	  Description: Método que imprime los totales de la una orden de compra
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_soc;
		$li_totalgasto=0;
		$li_totalcargo=0;
		$li_totalgeneral=0;
		if($as_tipo=="B")
		{
			for ($li_fila=1;$li_fila<=$ai_totbieser;$li_fila++)
			{ 
				$li_moncue= $io_funciones_soc->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
          		$li_carart= $io_funciones_soc->uf_obtenervalor("txtcarart".$li_fila,"0,00");
				$li_moncue= str_replace(".","",$li_moncue);
				$li_moncue= str_replace(",",".",$li_moncue);	
				$li_carart= str_replace(".","",$li_carart);
				$li_carart= str_replace(",",".",$li_carart);
				$li_totalgasto=$li_totalgasto+$li_moncue;
				$li_totalcargo=$li_totalcargo+$li_carart;
			}
		}
		else
		{
			for ($li_fila=1;$li_fila<=$ai_totbieser;$li_fila++)
			{ 
				$li_moncue= $io_funciones_soc->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
				$li_carser= $io_funciones_soc->uf_obtenervalor("txtcarser".$li_fila,"0,00");
				$li_moncue= str_replace(".","",$li_moncue);
				$li_moncue= str_replace(",",".",$li_moncue);	
				$li_carser= str_replace(".","",$li_carser);
				$li_carser= str_replace(",",".",$li_carser);
				$li_totalgasto=$li_totalgasto+$li_moncue;
				$li_totalcargo=$li_totalcargo+$li_carser;
			}
		}
		$li_totalgeneral=$li_totalgasto+$li_totalcargo;
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
	function uf_load_total($li_totalgasto,$li_totalcargo,$li_totalgeneral)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal ---> valor del subtotal
		//				   ai_cargos ---> valor total de los cargos
		//				   ai_total ---> total de la solicitu de pago
		//	  Description: Método que imprime los totales de la una orden de compra
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
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
	function uf_load_bienes($as_numordcom,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: private
		//	    Arguments: as_numordcom  // numero de la ordem de compra 
		//                 $as_tipsol  ---> tipo de la solicitud sep o soc
		//	  Description: Método que busca los bienes de la orden de compra  y los imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $ls_disabled;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad";
		$lo_title[5]="Precio/Unid.";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cr&eacute;ditos"; 
		$lo_title[8]="Total";
        $lo_title[9]="";
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_bienes($as_numordcom,$as_tipsol);
		$li_fila=0;
		$ls_estmodpart=$io_registro_orden->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
		  if($as_tipsol=="SOC")
		   { 
			   $lo_title[10]="";
		   }
		}
		while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codart       = trim($row["codart"]);
			$ls_denart       = $row["denart"];
			$ls_unidad       = $row["unidad"];
			$li_canart       = $row["canart"];
		    $li_preart       = $row["preuniart"];
		    $li_totart       = $row["montotart"];
			$ls_spgcuenta    = trim($row["spg_cuenta"]);
			$li_unimed       = $row["unimed"];
			$ls_numsolord    = $row["numsol"]; 
			if($ls_numsolord!="")
			{
				$ls_coduniadmsep = trim($row["coduniadm"]);
				$ls_denuniadmsep = $row["denuniadm"];
			}
			else
			{
			    $ls_coduniadmsep = "";
				$ls_denuniadmsep = "";
			}
			$ls_codestpro    = trim($row["codestpro1"]).trim($row["codestpro2"]).trim($row["codestpro3"]).trim($row["codestpro4"]).trim($row["codestpro5"]);
			$ls_estcla       = trim($row["estcla"]);
			if($ls_numsolord!="")
			{
				$rs_data1 = $io_registro_orden->uf_buscar_coduniadmsep();
				while($row=$io_registro_orden->io_sql->fetch_row($rs_data1))	  
				{ 
				   $ls_coduniadmsep = $row["coduniadm"]; 
				   $ls_denuniadmsep = $row["denuniadm"];
				}
            }
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
			$li_carart=abs($li_totart-$li_subtotart);
			$li_subtotart=number_format($li_subtotart,2,",",".");
			$li_totart=number_format($li_totart,2,",",".");
			$li_canart=number_format($li_canart,2,",",".");
			$li_preart=number_format($li_preart,2,",",".");
			$li_carart=number_format($li_carart,2,",",".");
			
 			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."       id=txtcodart".$li_fila."    class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>".
			                         "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									 "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                         "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                         "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>".
									 "<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."       id=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly>".
			                        "<input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'>".
									"<input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'>".
									"<input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."       id=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila."  style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
			$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."       id=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
			$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila."    id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."       id=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
			$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."       id=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
									" <input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila." value='".$ls_spgcuenta."'> ".
									" <input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila."    value='".$li_unimed."'>";

					
//			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila."    class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>".
//			                        "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila."  value='".$ls_numsolord."'>".
//									"<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila."  value='".$ls_coduniadmsep."'>";
//			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    id=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly>"."<input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila."  value='".$ls_denuniadmsep."'><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
//			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    id=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value='".$li_canart."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
//			$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."'); $ls_disabled><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
//			$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    id=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_preart."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
//			$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".$li_subtotart."' readonly>";
//			$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    id=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value='".$li_carart."'    readonly>";
//			$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    id=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value='".$li_totart."'    readonly>".
//									"<input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ".
//								    "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
//			                        "<input type=text name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
//			                        "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>".
//									"<input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila."     value='".$li_unimed."'>";
			$lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
            if ($ls_estmodpart==1)
			{ 
			  if($as_tipsol=="SOC")
			  {
				$lo_object[$li_fila][10]="<a href=javascript:ue_cambiar_partida_bien('".$li_fila."','".$ls_codestpro."','".$ls_spgcuenta."','".$ls_estcla."','1');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
			  }
			}
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila."    class=sin-borde style=text-align:center size=22 value=''  readonly><input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value=''><input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila."  value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    id=txtdenart".$li_fila."    class=sin-borde style=text-align:left   size=20 value=''  readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value=''><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    id=txtcanart".$li_fila."    class=sin-borde style=text-align:right size=8  value=''   onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."'); $ls_disabled><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
		$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    id=txtpreart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
		$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value=''  readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    id=txtcarart".$li_fila."    class=sin-borde style=text-align:right  size=10 value=''  readonly>";
		$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    id=txttotart".$li_fila."    class=sin-borde style=text-align:right  size=14 value=''  readonly>".
								" <input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila." value=''>".
							    "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='' readonly>".
			                    "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='' readonly>".
			                    "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='' readonly>".
								" <input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila."    value=''>";
		$lo_object[$li_fila][9]="";
	    if ($ls_estmodpart==1)
			{ 
			  if($as_tipsol=="SOC")
			  {
				$lo_object[$li_fila][10]="";
			  }
			}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogo_bienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_registro_orden);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Bienes","gridbienes");
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_creditos($as_titulo,$as_numordcom,$as_tipo,$as_tipsol,$as_estcondat)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_titulo ---> titulo de bienes o servicios
		//                 as_tipo ---> tipo de la orden de compra si es de bienes ó de servicios
		//	  Description: Método que busca los creditos de una orden de compra y las imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo, $li_cuenta;
		global $io_grid, $io_funciones_soc, $la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
		       $li_loncodestpro4,$li_loncodestpro5;
		// Titulos del Grid
		$lo_title[1]=$as_titulo;
		$lo_title[2]="C&oacute;digo";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Base Imponible";
		$lo_title[5]="Monto Otros Cr&eacute;ditos";
		$lo_title[6]="Sub-Total";
		$lo_object[0]="";
		switch($as_tipo)
		{
			case "B": // Si es de Bienes de la orden de compra
				$ls_tabla = "soc_dta_cargos";
				$ls_campo = "codart";
			break;
			
			case "S": // Si es de Servicios de la orden de compra 
				$ls_tabla = "soc_dts_cargos";
				$ls_campo = "codser";
			break;
		}
		$ls_codigoartser="";
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_cargos($as_numordcom,$ls_tabla,$ls_campo,"numordcom",$ls_codigoartser,$as_tipsol);
		$li_fila=0;
		$ls_estmodpart=$io_registro_orden->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
		   if($as_tipsol=="SOC")
		    { 
				$lo_title[7]=""; 
		    }
		}
		while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codservic	= trim($row["codigo"]); 
			$ls_codcar	 	= trim($row["codcar"]);
			$ls_dencar		= $row["dencar"];
			$li_bascar		= number_format($row["monbasimp"],2,",",".");
			$li_moncar	    = number_format($row["monimp"],2,",",".");
			$li_subcargo    = number_format($row["monto"],2,",",".");
			$ls_spg_cuenta  = $row["spg_cuenta"]; 
			$ls_formula		= $row["formula"];
			$ls_numsep		= trim($row["numsol"]); 
			$ls_codestpro1     = trim($row["codestpro1"]);
			$ls_codestpro2     = trim($row["codestpro2"]);
			$ls_codestpro3     = trim($row["codestpro3"]);
			$ls_codestpro4     = trim($row["codestpro4"]);
			$ls_codestpro5     = trim($row["codestpro5"]);
			$ls_estcla     = trim($row["estcla"]);
			$ls_codprog=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5; 
			if($as_tipsol=="SOC")
            {
				$ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
				$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
				$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
				$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				if ($li_estmodest=='2')
				   {
					 $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					 $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
					 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5; 
				   }
			   $ls_cuenta=$ls_spg_cuenta;
			   $ls_codestpre=$ls_codprog;
			   $lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codservic."' readonly>".
									   "<input name=hidnumsepcar".$li_fila." type=hidden id=hidnumsepcar".$li_fila." value='".$ls_numsep."'>".
									   "<input type=hidden name=txtcodgascre".$li_fila." id=txtcodgascre".$li_fila."  value='".$ls_codestpre."' readonly>".
									   "<input type=hidden name=txtcodspgcre".$li_fila." id=txtcodspgcre".$li_fila."  value='".$ls_cuenta."' readonly>".
									   "<input type=hidden name=txtstatuscre".$li_fila." id=txtstatuscre".$li_fila."  value='".$ls_estcla."' readonly>";
			   $lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
			   $lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
			   $lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".$li_bascar."' readonly>";
			   $lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".$li_moncar."' readonly>";
			   $lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".$li_subcargo."' readonly>".
									   "<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
									   "<input name=codprogcargo".$li_fila."  type=hidden id=codprogcargo".$li_fila."  value='".$ls_codprog."'>".
									   "<input type=hidden name=estclacargo".$li_fila." id=estclacargo".$li_fila." value='".$ls_estcla."'>".	
									   "<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
			  // $lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";                        
				if ($ls_estmodpart==1)
				{ 
				  if($as_tipsol=="SOC")
				   { 
					  $lo_object[$li_fila][7]="<a href=javascript:ue_cambiar_creditos('".$li_fila."','".$ls_codprog."','".$ls_cuenta."','".$ls_estcla."','2');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
				   }
				}					
			}
        
		 
		}
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Otros Cr&eacute;ditos","gridcreditos");
		unset($io_registro_orden);		
		print "<table width='840' height='22' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "        <tr>";
		print "          <td width='175' height='22' align='right'><div align='left'><input name='btncrear' type='button' class='boton' id='btncerrar' value='Crear Asiento' onClick='javascript: ue_crear_asiento();'></div></td>";
		print "        </tr>";
		print "</table>";
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numordcom,$as_estcondat,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de  de la orden de compra
		//                 as_estcondat  ---> tipo de la orden de compra si es de bienes ó de servicios
		//                 as_tipsol  ---> tipo si es solicitud de ejecucion presupuetaria o orden de compra
		//	  Description: Método que busca las cuentas presupuestarias asociadas a una orden de compra
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
		       $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1]="Estructura Presupuestaria";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$io_dscuentas = $io_registro_orden->uf_load_cuentas($as_numordcom,$as_estcondat,$as_tipsol);
		$li_fila=0;
		if($io_dscuentas!=false)
		{
			$li_totrow=$io_dscuentas->getRowCount("spg_cuenta");
			for($li_i=1;($li_i<=$li_totrow);$li_i++)
			{
				$li_monto=$io_dscuentas->data["total"][$li_i];
				if($li_monto>0)
				{ 
					$li_fila++;
					$ls_codestpro1 = trim($io_dscuentas->data["codestpro1"][$li_i]);
					$ls_codestpro2 = trim($io_dscuentas->data["codestpro2"][$li_i]); 
					$ls_codestpro3 = trim($io_dscuentas->data["codestpro3"][$li_i]);
					$ls_codestpro4 = trim($io_dscuentas->data["codestpro4"][$li_i]);
					$ls_codestpro5 = trim($io_dscuentas->data["codestpro5"][$li_i]);
		        	$ls_codestpre  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					$ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
					$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
					$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
					$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
					if ($li_estmodest=='2')
					   {
					     $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					     $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
					     $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5; 
					   }
					$ls_estcla = trim($io_dscuentas->data["estcla"][$li_i]);
					$li_moncue = number_format($io_dscuentas->data["total"][$li_i],2,",",".");
					$ls_cuenta = trim($io_dscuentas->data["spg_cuenta"][$li_i]);
				
	
				    $lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=80 value='".$ls_codestpro."' readonly>";
					$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=30 value='".$ls_cuenta."' readonly>";
					$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=30 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."'>".
					                        "<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codestpre."'>".
											"<input type=hidden name=estclapre".$li_fila." id=estclapre".$li_fila." value='".$ls_estcla."'>";
					//$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_estcondat."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
											
				}
			}
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=80 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=30 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value=''><input type=hidden name=estclapre".$li_fila." id=estclapre".$li_fila." value=''>";        

		print "<p>&nbsp;</p>";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg('".$li_fila."');'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_registro_orden);
	}// end function uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_cargo($as_numordcom,$as_estcondat,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de  de la orden de compra
		//                 as_estcondat  ---> tipo de la orden de compra si es de bienes ó de servicios
		//	  Description: Método que busca las cuentas asociadas a los cargos de una orden de compra 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo,$li_estmodest,$li_loncodestpro1,
		       $li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
		// Titulos el Grid
		$lo_title[1]="Cr&eacute;dito";
		$lo_title[2]="Estructura Presupuestaria";
		$lo_title[3]="Cuenta";
		$lo_title[4]="Monto";
		//$lo_title[5]=""; 

		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_cuentas_cargo($as_numordcom,$as_estcondat,$as_tipsol);
		$li_fila=0;
		while ($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
			    $ls_codcargo   = trim($row["codcar"]);
				$ls_estcla     = $row["estcla"];
			    $ls_codestpro1 = trim($row["codestpro1"]);
				$ls_codestpro2 = trim($row["codestpro2"]);
				$ls_codestpro3 = trim($row["codestpro3"]);
				$ls_codestpro4 = trim($row["codestpro4"]);
				$ls_codestpro5 = trim($row["codestpro5"]);
				$ls_codestcar  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		 
                $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
				$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
				$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
				$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				if ($li_estmodest==2)
				   {
                     $ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
					 $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					 $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
					 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;						   
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
				
			    $ls_cuenta     = trim($row["spg_cuenta"]);
			    $li_moncue     = number_format($row["total"],2,",",".");
			    $lo_object[$li_fila][1] = "<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=12 value='".$ls_codcargo."' readonly>";
			    $lo_object[$li_fila][2] = "<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=75 value='".$ls_codestpro."' readonly title='".$ls_codestpro.' - '.$ls_denestcla."'>";
			    $lo_object[$li_fila][3] = "<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
			    $lo_object[$li_fila][4] = "<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' readonly>".
			   						      "<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codestcar."'>".
										  "<input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value='".$ls_estcla."'>";
			    //$lo_object[$li_fila][5] = "<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_estcondat."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=12 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=75 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value=''><input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Otros Cr&eacute;ditos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas Otros Cr&eacute;ditos","gridcuentascargos");
		unset($io_registro_orden);
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numordcom,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_tipsol  ---> tipo de la solicitud si es sep o soc
		//	  Description: Método que busca los servicios de la orden de compra y los imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cr&eacute;ditos";
		$lo_title[7]="Total";
		$lo_title[8]="";
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_servicios($as_numordcom,$as_tipsol);
		$li_fila=0;
		$ls_estmodpart=$io_registro_orden->uf_validar_cambio_imputacion();//verifica si tiene permiso para modificar las partidas
		if ($ls_estmodpart==1)
		{
		   if($as_tipsol=="SOC")
			{
			   $lo_title[9]="";
			}
		}
		while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codser       = trim($row["codser"]);
			$ls_denser       = $row["denser"];
			$li_canser       = $row["canser"];
			$li_preser       = $row["monuniser"];
			$li_subtotser    = $li_preser*$li_canser;
			$li_totser       = $row["montotser"];
			$li_carser       = $li_totser-$li_subtotser;
			$ls_spgcuenta    = trim($row["spg_cuenta"]);
			$ls_codestpro    = trim($row["codestpro1"]).trim($row["codestpro2"]).trim($row["codestpro3"]).trim($row["codestpro4"]).trim($row["codestpro5"]);
			$ls_estcla       = trim($row["estcla"]);
			$ls_numsolord    = trim($row["numsol"]);
			$ls_coduniadmsep = trim($row["coduniadm"]);
			$ls_denuniadmsep = $row["denuniadm"];
			
			$li_canser=number_format($li_canser,2,",",".");
			$li_preser=number_format($li_preser,2,",",".");
			$li_subtotser=number_format($li_subtotser,2,",",".");
			$li_carser=number_format($li_carser,2,",",".");
			$li_totser=number_format($li_totser,2,",",".");
			
			$ls_codproser=$ls_codestpro;
			$ls_cuentaser=$ls_spgcuenta; 
			$ls_estclaser=$ls_estcla;
			
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>".
			                        "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									"<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>".
						            "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                        "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                        "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly>".
			                        "<input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'>".
			                        "<input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'>".
			                        "<input type=hidden name=estcla".$li_fila."       id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
			$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'>";

//			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly><input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila."  value='".$ls_numsolord."'><input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila."  value='".$ls_coduniadmsep."'>";
//			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila."  value='".$ls_denuniadmsep."'>".
//			                        "<input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'>".
//								    "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codproser."' readonly>".
//			                        "<input type=text name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_cuentaser."' readonly>".
//			                        "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estclaser."' readonly>".
//									"<input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
//			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right   size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
//			$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
//			$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$li_subtotser."' readonly>";
//			$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$li_carser."' readonly>";
//			$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$li_totser."' readonly>".
//									"<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
			$lo_object[$li_fila][8] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value=''></a>";
		    if ($ls_estmodpart==1)
			{ 
			  if($as_tipsol=="SOC")
			  {
				$lo_object[$li_fila][9]="<a href=javascript:ue_cambiar_partida_servicio('".$li_fila."','".$ls_codproser."','".$ls_cuentaser."','".$ls_estclaser."','3');><img src=../shared/imagebank/mas.gif title=Cambiar width=14 height=14 border=0></a>";
			  }
			}
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly><input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila."  value=''><input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila."  value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila."  value=''><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
		$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''> ";
		$lo_object[$li_fila][8] ="";
		if ($ls_estmodpart==1)
		{ 
		  if($as_tipsol=="SOC")
			{
			   $lo_object[$li_fila][9]=" ";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogo_servicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_registro_orden);
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes_sep($ai_totalbienes,$as_numsol,$as_tipsol,$as_tipconpro,&$ld_subordcom,&$ld_creordcom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes_sep
		//		   Access: private
		//	    Arguments: ai_totalbienes  // total de filas a imprimir
		//                 as_numsol  ---> numero de solicitud a imprimir 
		//	  Description: Método que imprime el grid de los Bienes de la pantalla  y el seleccionado
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc,$io_dts_sep;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dts_bienes = new class_datastore();
		$io_dts_sep = new class_datastore();
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Unidad";
		$lo_title[5]="Precio/Unid.";
		$lo_title[6]="Sub-Total";
		$lo_title[7]="Cr&eacute;ditos"; 
		$lo_title[8]="Total";
		$lo_title[9]="";
		// Recorremos los bienes del grid y los pintamos 
		for($li_fila=1;$li_fila<=$ai_totalbienes;$li_fila++)
		{ 
			$ls_numsolord_opener    = $io_funciones_soc->uf_obtenervalor("txtnumsolord".$li_fila,"");
			$ls_coduniadmsep_opener = $io_funciones_soc->uf_obtenervalor("txtcoduniadmsep".$li_fila,"");
			$ls_denuniadmsep_opener = $io_funciones_soc->uf_obtenervalor("txtdenuniadmsep".$li_fila,"");
			$ls_codestpro           = $io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
			$ls_estcla              = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");
			$ls_codart_opener       = $io_funciones_soc->uf_obtenervalor("txtcodart".$li_fila,"");
			$ls_denart_opener       = $io_funciones_soc->uf_obtenervalor("txtdenart".$li_fila,"");
			$li_canart_opener       = $io_funciones_soc->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			$ls_unidad_opener       = $io_funciones_soc->uf_obtenervalor("cmbunidad".$li_fila,"M");
			$ld_preart_opener       = $io_funciones_soc->uf_obtenervalor("txtpreart".$li_fila,"0,00");
			$ld_subtotart_opener    = $io_funciones_soc->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
			$ld_carart_opener       = $io_funciones_soc->uf_obtenervalor("txtcarart".$li_fila,"0,00");
			$ld_totart_opener       = $io_funciones_soc->uf_obtenervalor("txttotart".$li_fila,"0,00");
			$ls_spgcuenta_opener    = trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
			$li_unimed_opener       = $io_funciones_soc->uf_obtenervalor("txtunidad".$li_fila,"");
           
			if($ls_unidad_opener=="M") // Si es al Mayor
			{
				$ld_canart_opener=$li_canart_opener*$li_unimed_opener;
			}
			else // Si es al Detal
			{
				$ld_canart_opener=$li_canart_opener;
			}
			
			$ld_preart_opener    = str_replace(".","",$ld_preart_opener);
			$ld_preart_opener    = str_replace(",",".",$ld_preart_opener);		
			$li_canart_opener    = str_replace(".","",$li_canart_opener);
			$li_canart_opener    = str_replace(",",".",$li_canart_opener);		
			$ld_subtotart_opener = str_replace(".","",$ld_subtotart_opener);
			$ld_subtotart_opener = str_replace(",",".",$ld_subtotart_opener);		
			$ld_carart_opener    = str_replace(".","",$ld_carart_opener);
			$ld_carart_opener    = str_replace(",",".",$ld_carart_opener);		
			$ld_totart_opener    = str_replace(".","",$ld_totart_opener);
			$ld_totart_opener    = str_replace(",",".",$ld_totart_opener);		
			
			if($ls_codart_opener!="")
			{
				$ld_subordcom += $ld_subtotart_opener;
		  	    $ld_creordcom += $ld_carart_opener;
				$io_dts_bienes->insertRow("numsolord",$ls_numsolord_opener);	
				$io_dts_bienes->insertRow("coduniadmsep",$ls_coduniadmsep_opener);
				$io_dts_bienes->insertRow("denuniadmsep",$ls_denuniadmsep_opener);	
				$io_dts_bienes->insertRow("codart",$ls_codart_opener);			
				$io_dts_bienes->insertRow("denart",$ls_denart_opener);			
				$io_dts_bienes->insertRow("canart",$li_canart_opener);			
				$io_dts_bienes->insertRow("unidad",$ls_unidad_opener);	
				$io_dts_bienes->insertRow("preart",$ld_preart_opener);	
				$io_dts_bienes->insertRow("subtotart",$ld_subtotart_opener);	
				$io_dts_bienes->insertRow("carart",$ld_carart_opener);	
				$io_dts_bienes->insertRow("totart",$ld_totart_opener);	
				$io_dts_bienes->insertRow("spgcuenta",$ls_spgcuenta_opener);	
			    $io_dts_bienes->insertRow("unimed",$li_unimed_opener);
				$io_dts_bienes->insertRow("codestpro",$ls_codestpro);
			    $io_dts_bienes->insertRow("estcla",$ls_estcla);
		    }	
		}//for
		
		// Buscamos los detalles de los bienes de la sep seleccionada
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_bienes($as_numsol,$as_tipsol);
		while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		{
			$ls_codart_sep    = trim($row["codart"]);
			$ls_denart_sep    = $row["denart"];
			$ls_unidad_sep    = trim($row["unidad"]);
			$li_canart_sep    = trim($row["canart"]);
		    $ld_preart_sep    = $row["monpre"];
		    $ld_totart_sep    = $row["monart"];
			$ls_spgcuenta_sep = trim($row["spg_cuenta"]);
			$li_unimed_sep    = $row["unimed"];
			$ls_coduniadmsep  = trim($row["coduniadm"]);
			$ls_codestpro1    = trim($row["codestpro1"]);
			$ls_codestpro2    = trim($row["codestpro2"]);
			$ls_codestpro3    = trim($row["codestpro3"]);
			$ls_codestpro4    = trim($row["codestpro4"]);
			$ls_codestpro5    = trim($row["codestpro5"]);
			$ls_codestpro     = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_estcla        = trim($row["estcla"]);
			$ls_denuniadmsep  = $row["denuniadm"];
			if($ls_unidad_sep=="M") // Si es al Mayor
			{
				$ls_maysel="selected";
				$ls_detsel="";
				$ld_subtotart_sep=$ld_preart_sep*($li_canart_sep*$li_unimed_sep);
				$ld_canart_sep=$li_canart_sep*$li_unimed_sep;
			}
			else // Si es al Detal
			{
				$ls_maysel="";
				$ls_detsel="selected";
				$ld_subtotart_sep=$ld_preart_sep*$li_canart_sep;
				$ld_canart_sep=$li_canart_sep;
			}
			if(($as_tipconpro=="O")||($as_tipconpro=="E"))
			{
				$ld_totart_sep= number_format($ld_totart_sep,2,'.','');
				$ld_subtotart_sep= number_format($ld_subtotart_sep,2,'.','');
				$ld_carart_sep=$ld_totart_sep-$ld_subtotart_sep;
			}
			else
			{
               $ld_carart_sep = "0,00";
			   $ld_totart_sep = $ld_subtotart_sep;
			}
			$ld_subordcom += $ld_subtotart_sep;
		  	$ld_creordcom += $ld_carart_sep;
			$io_dts_bienes->insertRow("numsolord",$as_numsol);
			$io_dts_bienes->insertRow("coduniadmsep",$ls_coduniadmsep);
			$io_dts_bienes->insertRow("denuniadmsep",$ls_denuniadmsep);
			$io_dts_bienes->insertRow("codart",$ls_codart_sep);			
			$io_dts_bienes->insertRow("denart",$ls_denart_sep);			
			$io_dts_bienes->insertRow("canart",$ld_canart_sep);			
			$io_dts_bienes->insertRow("unidad",$ls_unidad_sep);	
			$io_dts_bienes->insertRow("preart",$ld_preart_sep);	
			$io_dts_bienes->insertRow("subtotart",$ld_subtotart_sep);	
			$io_dts_bienes->insertRow("carart",$ld_carart_sep);	
			$io_dts_bienes->insertRow("totart",$ld_totart_sep);	
			$io_dts_bienes->insertRow("spgcuenta",$ls_spgcuenta_sep);	
			$io_dts_bienes->insertRow("codestpro",$ls_codestpro);
			$io_dts_bienes->insertRow("estcla",$ls_estcla);
			$io_dts_bienes->insertRow("unimed",$li_unimed_sep);	
		}//while
		$li_rows=$io_dts_bienes->getRowCount('codart');
		for($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		{
		    $ls_numsolord=$io_dts_bienes->getValue('numsolord',$li_fila);
		    $ls_codart=$io_dts_bienes->getValue('codart',$li_fila);
		   
			$io_dts_sep->insertRow("numsolord",$ls_numsolord);	
			$io_dts_sep->insertRow("codart",$ls_codart);			
		}
		$li_rows=$io_dts_bienes->getRowCount('codart');	
		for ($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		    {
		      $ls_numsolord    = $io_dts_bienes->getValue('numsolord',$li_fila);
		      $ls_coduniadmsep = $io_dts_bienes->getValue('coduniadmsep',$li_fila);
		      $ls_denuniadmsep = $io_dts_bienes->getValue('denuniadmsep',$li_fila);
		      $ls_codart       = $io_dts_bienes->getValue('codart',$li_fila);
		      $ls_denart       = $io_dts_bienes->getValue('denart',$li_fila);
		      $li_canart       = $io_dts_bienes->getValue('canart',$li_fila);
		      $ls_unidad       = $io_dts_bienes->getValue('unidad',$li_fila);
		      $ld_preart       = $io_dts_bienes->getValue('preart',$li_fila);
		      $ld_subtotart    = $io_dts_bienes->getValue('subtotart',$li_fila);
		      $ld_carart       = $io_dts_bienes->getValue('carart',$li_fila);
		      $ld_totart       = $io_dts_bienes->getValue('totart',$li_fila);
		      $ls_codestpro    = $io_dts_bienes->getValue('codestpro',$li_fila);
			  $ls_estcla       = $io_dts_bienes->getValue('estcla',$li_fila);
			  $ls_spgcuenta    = trim($io_dts_bienes->getValue('spgcuenta',$li_fila));
		      $li_unimed       = $io_dts_bienes->getValue('unimed',$li_fila);
		      if ($ls_unidad=="M") // Si es al Mayor
		         {
			       $ls_maysel="selected";
			       $ls_detsel="";
		         }
		      else // Si es al Detal
		         {
			       $ls_maysel="";
			       $ls_detsel="selected";
		         }
		      
 			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."       id=txtcodart".$li_fila."    class=sin-borde style=text-align:center size=22 value='".$ls_codart."'    readonly>".
			                         "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									 "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                         "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                         "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>".
									 "<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>";
		      $lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    id=txtdenart".$li_fila." class=sin-borde style=text-align:left   size=20 value='".$ls_denart."'    readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
		      $lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    id=txtcanart".$li_fila." class=sin-borde style=text-align:right size=8  value='".number_format($li_canart,2,',','.')."'     onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
		      $lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
		      $lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    id=txtpreart".$li_fila." class=sin-borde style=text-align:right  size=10 value='".number_format($ld_preart,2,',','.')."' 	  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
		      $lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".number_format($ld_subtotart,2,',','.')."' readonly>";
		      $lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    id=txtcarart".$li_fila." class=sin-borde style=text-align:right  size=10 value='".number_format($ld_carart,2,',','.')."'    readonly>";
		      $lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    id=txttotart".$li_fila." class=sin-borde style=text-align:right  size=14 value='".number_format($ld_totart,2,',','.')."'    readonly>".
								      "<input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila." value='".$ls_spgcuenta."'> ".
								      "<input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila." value='".$li_unimed."'>";
		      $lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."    id=txtcodart".$li_fila." class=sin-borde style=text-align:center size=22 value=''  readonly><input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value=''><input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."    id=txtdenart".$li_fila." class=sin-borde style=text-align:left   size=20 value=''  readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value=''><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."    id=txtcanart".$li_fila." class=sin-borde style=text-align:right size=8  value=''   onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<select name=cmbunidad".$li_fila." style='width:60px' onChange=ue_procesar_monto('B','".$li_fila."');><option value=D ".$ls_detsel.">Detal</option><option value=M ".$ls_maysel.">Mayor</option></select>";
		$lo_object[$li_fila][5]="<input type=text name=txtpreart".$li_fila."    id=txtpreart".$li_fila." class=sin-borde style=text-align:right  size=10 value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>";
		$lo_object[$li_fila][6]="<input type=text name=txtsubtotart".$li_fila." id=txtsubtotart".$li_fila." class=sin-borde style=text-align:right  size=14 value=''  readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txtcarart".$li_fila."    id=txtcarart".$li_fila." class=sin-borde style=text-align:right  size=10 value=''  readonly>";
		$lo_object[$li_fila][8]="<input type=text name=txttotart".$li_fila."    id=txttotart".$li_fila." class=sin-borde style=text-align:right  size=14 value=''  readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila." id=txtspgcuenta".$li_fila." value=''> ".
								"<input type=hidden name=txtunidad".$li_fila."    id=txtunidad".$li_fila."    value=''>";
		$lo_object[$li_fila][9]="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
	    print " 	 <td height='22' align='left'><a href='javascript:ue_catalogo_bienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Bienes","gridbienes");
		unset($io_registro_orden);
		unset($io_dts_bienes);
	}// end function uf_print_bienes_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_servicios_sep($ai_total,$as_numsol,$as_tipsol,$as_tipconpro,&$ld_subordcom,&$ld_creordcom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios_sep
		//		   Access: private
		//	    Arguments: ai_total  ---> total de filas a imprimir
		//                 as_numsol ---> numero de solicitud seleccionada
		//                 as_tipsol ---> tipo de solicitud sep o soc
		//	  Description: Método que imprime y busca los servicios en una sep y pinta los ya existentes en el grid
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007					Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dts_servicios = new class_datastore();
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="Precio";
		$lo_title[5]="Sub-Total";
		$lo_title[6]="Cr&eacute;ditos";
		$lo_title[7]="Total";
		$lo_title[8]="";

		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_numsolord    = $io_funciones_soc->uf_obtenervalor("txtnumsolord".$li_fila,"");
			$ls_coduniadmsep = $io_funciones_soc->uf_obtenervalor("txtcoduniadmsep".$li_fila,"");
			$ls_denuniadmsep = $io_funciones_soc->uf_obtenervalor("txtdenuniadmsep".$li_fila,"");
			$ls_codestpro    = $io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
			$ls_estcla       = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");
			$ls_codser       = $io_funciones_soc->uf_obtenervalor("txtcodser".$li_fila,"");
			$ls_denser       = $io_funciones_soc->uf_obtenervalor("txtdenser".$li_fila,"");
			$li_canser       = $io_funciones_soc->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			$ld_preser       = $io_funciones_soc->uf_obtenervalor("txtpreser".$li_fila,"0,00");
			$ld_subtotser    = $io_funciones_soc->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
			$ld_carser       = $io_funciones_soc->uf_obtenervalor("txtcarser".$li_fila,"0,00");
			$ld_totser       = $io_funciones_soc->uf_obtenervalor("txttotser".$li_fila,"0,00");
			$ls_spgcuenta    = trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
			$ls_hidspgcuentas= trim($io_funciones_soc->uf_obtenervalor("hidspgcuentas".$li_fila,""));

			$ld_preser    = str_replace(".","",$ld_preser);
			$ld_preser    = str_replace(",",".",$ld_preser);	
			$li_canser    = str_replace(".","",$li_canser);
			$li_canser    = str_replace(",",".",$li_canser);	
			$ld_subtotser = str_replace(".","",$ld_subtotser);
			$ld_subtotser = str_replace(",",".",$ld_subtotser);		
			$ld_carser    = str_replace(".","",$ld_carser);
			$ld_carser    = str_replace(",",".",$ld_carser);		
			$ld_totser    = str_replace(".","",$ld_totser);
			$ld_totser    = str_replace(",",".",$ld_totser);		
						
			if ($ls_codser!="")
			   {
				 $ld_subordcom += $ld_subtotser;
		  	     $ld_creordcom += $ld_carser;
				 $io_dts_servicios->insertRow("numsolord",$ls_numsolord);
				 $io_dts_servicios->insertRow("coduniadmsep",$ls_coduniadmsep);	
				 $io_dts_servicios->insertRow("denuniadmsep",$ls_denuniadmsep);	
				 $io_dts_servicios->insertRow("codser",$ls_codser);			
				 $io_dts_servicios->insertRow("denser",$ls_denser);			
				 $io_dts_servicios->insertRow("canser",$li_canser);			
				 $io_dts_servicios->insertRow("preser",$ld_preser);	
				 $io_dts_servicios->insertRow("subtotser",$ld_subtotser);	
				 $io_dts_servicios->insertRow("carser",$ld_carser);	
				 $io_dts_servicios->insertRow("totser",$ld_totser);	
				 $io_dts_servicios->insertRow("spgcuenta",$ls_spgcuenta);
				 $io_dts_servicios->insertRow("codestpro",$ls_codestpro);
			     $io_dts_servicios->insertRow("estcla",$ls_estcla);
				 $io_dts_servicios->insertRow("hidspgcuentas",$ls_hidspgcuentas);
			   }
		}
		//Buscamos los detalles de servicio de la sep seleccionada 	
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$rs_data = $io_registro_orden->uf_load_servicios($as_numsol,$as_tipsol);
		while($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		{
			$ls_codser       = $row["codser"];
			$ls_denser       = $row["denser"];
			$li_canser       = $row["canser"];
			$ld_preser       = $row["monpre"];
			$ld_subtotser    = $ld_preser*$li_canser;
			$ld_totser       = $row["monser"];
			$ls_spgcuenta    = trim($row["spg_cuenta"]);
			$ls_coduniadmsep = $row["coduniadm"];
			$ls_denuniadmsep = $row["denuniadm"];
			$ls_codestpro1   = trim($row["codestpro1"]);
			$ls_codestpro2   = trim($row["codestpro2"]);
			$ls_codestpro3   = trim($row["codestpro3"]);
			$ls_codestpro4   = trim($row["codestpro4"]);
			$ls_codestpro5   = trim($row["codestpro5"]);
			$ls_codestpro    = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_estcla       = trim($row["estcla"]);
			if(($as_tipconpro=="O")||($as_tipconpro=="E"))
			{
				$ld_totser= number_format($ld_totser,2,'.','');
				$ld_subtotser= number_format($ld_subtotser,2,'.','');
			    $ld_carser=$ld_totser-$ld_subtotser;
			}
			elseif($as_tipconpro=="F")
			{
               $ld_carser = "0,00";
			   $ld_totser = $ld_subtotser;
			}
			$ld_subordcom += $ld_subtotser;
			$ld_creordcom += $ld_carser;
			$io_dts_servicios->insertRow("numsolord",$as_numsol);
			$io_dts_servicios->insertRow("coduniadmsep",$ls_coduniadmsep);	
			$io_dts_servicios->insertRow("denuniadmsep",$ls_denuniadmsep);
			$io_dts_servicios->insertRow("codser",$ls_codser);			
			$io_dts_servicios->insertRow("denser",$ls_denser);			
			$io_dts_servicios->insertRow("canser",$li_canser);			
			$io_dts_servicios->insertRow("preser",$ld_preser);	
			$io_dts_servicios->insertRow("subtotser",$ld_subtotser);	
			$io_dts_servicios->insertRow("carser",$ld_carser);	
			$io_dts_servicios->insertRow("totser",$ld_totser);	
			$io_dts_servicios->insertRow("spgcuenta",$ls_spgcuenta);
			$io_dts_servicios->insertRow("codestpro",$ls_codestpro);
			$io_dts_servicios->insertRow("estcla",$ls_estcla);
			$io_dts_servicios->insertRow("hidspgcuentas",$ls_spgcuenta);
		}	
		//Recorremos el datastore llenado antenriormente para vaciar la informacion al grid
		$li_rows = $io_dts_servicios->getRowCount('codser');
		for ($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		    {
		      $ls_numsolord    = $io_dts_servicios->getValue('numsolord',$li_fila);
		      $ls_coduniadmsep = $io_dts_servicios->getValue('coduniadmsep',$li_fila);
		      $ls_denuniadmsep = $io_dts_servicios->getValue('denuniadmsep',$li_fila); 
		      $ls_codser       = $io_dts_servicios->getValue('codser',$li_fila);
		      $ls_denser       = $io_dts_servicios->getValue('denser',$li_fila);
		      $ls_codestpro    = $io_dts_servicios->getValue('codestpro',$li_fila);
		      $ls_estcla       = $io_dts_servicios->getValue('estcla',$li_fila);
			  $li_canser       = number_format($io_dts_servicios->getValue('canser',$li_fila),2,",",".");
		      $ld_preser       = number_format($io_dts_servicios->getValue('preser',$li_fila),2,",",".");
		      $ld_subtotser    = number_format($io_dts_servicios->getValue('subtotser',$li_fila),2,",",".");
		      $ld_carser       = number_format($io_dts_servicios->getValue('carser',$li_fila),2,",",".");
		      $ld_totser       = number_format($io_dts_servicios->getValue('totser',$li_fila),2,",",".");
			  $ls_spgcuenta    = trim($io_dts_servicios->getValue('spgcuenta',$li_fila));
			  $ls_hidspgcuentas= trim($io_dts_servicios->getValue('hidspgcuentas',$li_fila));
			  
			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='".$ls_codser."' readonly>".
			                        "<input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value='".$ls_numsolord."'>".
									"<input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value='".$ls_coduniadmsep."'>".
						            "<input type=hidden name=txtcodgas".$li_fila." id=txtcodgas".$li_fila."  value='".$ls_codestpro."' readonly>".
			                        "<input type=hidden name=txtcodspg".$li_fila." id=txtcodspg".$li_fila."  value='".$ls_spgcuenta."' readonly>".
			                        "<input type=hidden name=txtstatus".$li_fila." id=txtstatus".$li_fila."  value='".$ls_estcla."' readonly>";
			  $lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='".$ls_denser."' readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value='".$ls_denuniadmsep."'><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			  $lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right   size=9  value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			  $lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$ld_preser."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
			  $lo_object[$li_fila][5] = "<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='".$ld_subtotser."' readonly>";
			  $lo_object[$li_fila][6] = "<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='".$ld_carser."' readonly>";
			  $lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='".$ld_totser."' readonly>".
									    "<input type=hidden name=txtspgcuenta".$li_fila."  value='".$ls_spgcuenta."'> ";
		 	  $lo_object[$li_fila][8] = "<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0><input type=hidden name=hidspgcuentas".$li_fila."  value='".$ls_hidspgcuentas."'></a>";
		}
		$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=15 value='' readonly><input type=hidden name=txtnumsolord".$li_fila." id=txtnumsolord".$li_fila." value=''><input type=hidden name=txtcoduniadmsep".$li_fila." id=txtcoduniadmsep".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    class=sin-borde  style=text-align:left    size=30 value='' readonly><input type=hidden name=txtdenuniadmsep".$li_fila." id=txtdenuniadmsep".$li_fila." value=''><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    class=sin-borde  style=text-align:right  size=9  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
		$lo_object[$li_fila][4]="<input type=text name=txtpreser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>";
		$lo_object[$li_fila][5]="<input type=text name=txtsubtotser".$li_fila." class=sin-borde  style=text-align:right   size=15 value='' readonly>";
		$lo_object[$li_fila][6]="<input type=text name=txtcarser".$li_fila."    class=sin-borde  style=text-align:right   size=10 value='' readonly>";
		$lo_object[$li_fila][7]="<input type=text name=txttotser".$li_fila."    class=sin-borde  style=text-align:right   size=15 value='' readonly>".
								"<input type=hidden name=txtspgcuenta".$li_fila."  value=''><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][8] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' colspan='3' align='left'><a href='javascript:ue_catalogo_servicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Detalle de Servicios","gridservicios");
		unset($io_registro_orden);
		unset($io_dts_servicios);
	}// end function uf_print_servicios_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos_sep($as_titulo,$ai_totalcargos,$as_cargarcargos,$as_tipo,$as_numsol,$as_tipsol,$as_tipconpro,$ls_numordcom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: ai_total  ---> total de filas a imprimir
		//                 as_titulo ---> titulo de bienes o servicios
		//                 as_cargarcargos ---> si cargamos los cargos ó solo pintamos
		//                 as_tipo ---> tipo de SEP si es de bienes ó de servicios
		//                 as_numsol ---> numero de solicitud a buscar
		//                 as_tipsol ---> tipo de solicitud sep o soc
		//	  Description: Método que imprime el grid de créditos y busca los creditos de un Bien o un Servicio 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo, $li_cuenta;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dts_cargos=new class_datastore();
		// Titulos del Grid
		$lo_title[1]  = $as_titulo;
		$lo_title[2]  = "C&oacute;digo";
		$lo_title[3]  = "Denominaci&oacute;n";
		$lo_title[4]  = "Base Imponible";
		$lo_title[5]  = "Monto Otros Cr&eacute;ditos";
		$lo_title[6]  = "Sub-Total";
		//$lo_title[7]  = ""; 
		$lo_object[0] = "";
		// Recorrido de el grid de Cargos 
	    //print " ai_totalcargos ".$ai_totalcargos;
		for ($li_fila=1;$li_fila<=$ai_totalcargos;$li_fila++)
		    { 
			  $ls_codartser  = $io_funciones_soc->uf_obtenervalor("txtcodservic".$li_fila,""); 
			  $ls_codcar     = $io_funciones_soc->uf_obtenervalor("txtcodcar".$li_fila,"");
			  $ls_dencar     = $io_funciones_soc->uf_obtenervalor("txtdencar".$li_fila,"");
			  $ld_bascar     = $io_funciones_soc->uf_obtenervalor("txtbascar".$li_fila,"");
			  $ld_moncar     = $io_funciones_soc->uf_obtenervalor("txtmoncar".$li_fila,"");
			  $ld_subcargo   = $io_funciones_soc->uf_obtenervalor("txtsubcargo".$li_fila,"");
			  $ls_spg_cuenta = $io_funciones_soc->uf_obtenervalor("cuentacargo".$li_fila,"");
			  $ls_formula    = $io_funciones_soc->uf_obtenervalor("formulacargo".$li_fila,"");
			  $ls_numsep     = $io_funciones_soc->uf_obtenervalor("hidnumsepcar".$li_fila,""); 
			  /*$ls_codprog    =$io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
			   $ls_estcla     =$io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");*/
			  $ls_codprogcargo    =$io_funciones_soc->uf_obtenervalor("codprogcargo".$li_fila,""); 
			  $ls_estclacargo     =$io_funciones_soc->uf_obtenervalor("estclacargo".$li_fila,"");

			
			  $ld_bascar   = str_replace(".","",$ld_bascar);
			  $ld_bascar   = str_replace(",",".",$ld_bascar);		
			  $ld_moncar   = str_replace(".","",$ld_moncar);
			  $ld_moncar   = str_replace(",",".",$ld_moncar);		
			  $ld_subcargo = str_replace(".","",$ld_subcargo);
			  $ld_subcargo = str_replace(",",".",$ld_subcargo);		
			  
			  $io_dts_cargos->insertRow("codartser",$ls_codartser);			
			  $io_dts_cargos->insertRow("codcar",$ls_codcar);			
			  $io_dts_cargos->insertRow("dencar",$ls_dencar);			
			  $io_dts_cargos->insertRow("bascar",$ld_bascar);	
			  $io_dts_cargos->insertRow("moncar",$ld_moncar);	
			  $io_dts_cargos->insertRow("subcargo",$ld_subcargo);	
			  $io_dts_cargos->insertRow("cuentacargo",$ls_spg_cuenta);	
			  $io_dts_cargos->insertRow("formulacargo",$ls_formula);
			  $io_dts_cargos->insertRow("numsep",$ls_numsep);
			  $io_dts_cargos->insertRow("codprog",$ls_codprogcargo);
			  $io_dts_cargos->insertRow("estcla",$ls_estclacargo);
			 /* $io_dts_cargos->insertRow("codprogcargo",$ls_codprog);
			  $io_dts_cargos->insertRow("estclacargo",$ls_estcla);*/
			  			  	
		    }

		// Buscamos los cargos de la sep seleccionada
		switch($as_tipo)
		{
			case "B": // Si es de Bienes de la solicitud presupuestaria
				$ls_tabla = "sep_dta_cargos";
				$ls_campo = "codart";
			break;
			
			case "S": // Si es de Servicios de la orden de compra 
				$ls_tabla = "sep_dts_cargos";
				$ls_campo = "codser";
			break;
		} 
	    if(($as_tipconpro=="O")||($as_tipconpro=="E"))
	    {  
			require_once("sigesp_soc_c_registro_orden_compra.php");
			$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
			$rs_data_art = $io_registro_orden->uf_select_item_no_incorporados($as_numsol,$as_tipo);
			while ($row=$io_registro_orden->io_sql->fetch_row($rs_data_art))	  
			      { 
				    $ls_codigoartser = $row["codigoartser"];
				    $rs_data= $io_registro_orden->uf_load_cargossep($as_numsol,$ls_tabla,$ls_campo,"numsol",$ls_codigoartser,$as_tipsol);
			 	    while ($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
					  {  
					        $ls_codartser  = trim($row["codigo"]);
					        $ls_codcar     = trim($row["codcar"]);
					        $ls_dencar     = $row["dencar"];
					        $ld_bascar     = $row["monbasimp"];
					        $ld_moncar     = $row["monimp"];
					        $ld_subcargo   = $row["monto"];
					        $ls_spg_cuenta = trim($row["spg_cuenta"]);
					        $ls_formula    = $row["formula"];
							$ls_numsep     = trim($row["numsol"]);
							$ls_codestpro1     = trim($row["codestpro1"]);
							$ls_codestpro2     = trim($row["codestpro2"]);
							$ls_codestpro3     = trim($row["codestpro3"]);
							$ls_codestpro4     = trim($row["codestpro4"]);
							$ls_codestpro5     = trim($row["codestpro5"]);
							$ls_estcla     = trim($row["estcla"]); 
							$ls_codprog=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5; 
							if (!empty($ls_codartser))
							{
								$io_dts_cargos->insertRow("codartser",$ls_codartser);			
								$io_dts_cargos->insertRow("codcar",$ls_codcar);			
								$io_dts_cargos->insertRow("dencar",$ls_dencar);			
								$io_dts_cargos->insertRow("bascar",$ld_bascar);	
								$io_dts_cargos->insertRow("moncar",$ld_moncar);	
								$io_dts_cargos->insertRow("subcargo",$ld_subcargo);	
								$io_dts_cargos->insertRow("cuentacargo",$ls_spg_cuenta);	
								$io_dts_cargos->insertRow("formulacargo",$ls_formula);
								$io_dts_cargos->insertRow("numsep",$ls_numsep);
								$io_dts_cargos->insertRow("codprog",$ls_codprog);
								$io_dts_cargos->insertRow("estcla",$ls_estcla);	
							}
					  }
			      }	
		}   
//		print_r($io_dts_cargos->data);
//		print "<br>";
		//agrupamos por cuenta del cargo  y por codigo del articulo o el servicio     
		$io_dts_cargos->group_by(array('0'=>'codartser','1'=>'codcar','2'=>'numsep'),array('0'=>'bascar','1'=>'moncar','2'=>'subcargo'),'subcargo');
		$li_rows=$io_dts_cargos->getRowCount('codartser');
		for ($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		    {   
		      $ls_codartser  = trim($io_dts_cargos->getValue('codartser',$li_fila));
		      $ls_codcar	 = trim($io_dts_cargos->getValue('codcar',$li_fila));
		      $ls_dencar	 = $io_dts_cargos->getValue('dencar',$li_fila);
		      $ld_bascar	 = $io_dts_cargos->getValue('bascar',$li_fila);
		      $ld_moncar	 = $io_dts_cargos->getValue('moncar',$li_fila);
		      $ld_subcargo   = $io_dts_cargos->getValue('subcargo',$li_fila);
		      $ls_spg_cuenta = trim($io_dts_cargos->getValue('cuentacargo',$li_fila));
		      $ls_formula    = $io_dts_cargos->getValue('formulacargo',$li_fila);
			  $ls_numsep     = trim($io_dts_cargos->getValue('numsep',$li_fila));
			  $ls_codprog=$io_dts_cargos->getValue('codprog',$li_fila); 
			  $ls_estcla=$io_dts_cargos->getValue('estcla',$li_fila);

	       	
			$lo_object[$li_fila][1]="<input name=txtcodservic".$li_fila." type=text id=txtcodservic".$li_fila." class=sin-borde  size=22   style=text-align:center value='".$ls_codartser."' readonly>".
			                        "<input name=hidnumsepcar".$li_fila."  type=hidden id=hidnumsepcar".$li_fila." value='".$ls_numsep."'>".
									"<input type=hidden name=txtcodgascre".$li_fila." id=txtcodgascre".$li_fila."  value='".$ls_codprog."' readonly>".
			                        "<input type=hidden name=txtcodspgcre".$li_fila." id=txtcodspgcre".$li_fila."  value='".$ls_spg_cuenta."' readonly>".
			                        "<input type=hidden name=txtstatuscre".$li_fila." id=txtstatuscre".$li_fila."  value='".$ls_estcla."' readonly>";
		      $lo_object[$li_fila][2]="<input name=txtcodcar".$li_fila."    type=text id=txtcodcar".$li_fila."    class=sin-borde  size=10   style=text-align:center value='".$ls_codcar."' readonly>";
		      $lo_object[$li_fila][3]="<input name=txtdencar".$li_fila."    type=text id=txtdencar".$li_fila."    class=sin-borde  size=36   style=text-align:left   value='".$ls_dencar."' readonly>";
		      $lo_object[$li_fila][4]="<input name=txtbascar".$li_fila."    type=text id=txtbascar".$li_fila."    class=sin-borde  size=17   style=text-align:right  value='".number_format($ld_bascar,2,",",".")."' readonly>";
		      $lo_object[$li_fila][5]="<input name=txtmoncar".$li_fila."    type=text id=txtmoncar".$li_fila."    class=sin-borde  size=13   style=text-align:right  value='".number_format($ld_moncar,2,",",".")."' readonly>";
		      $lo_object[$li_fila][6]="<input name=txtsubcargo".$li_fila."  type=text id=txtsubcargo".$li_fila."  class=sin-borde  size=17   style=text-align:right  value='".number_format($ld_subcargo,2,",",".")."' readonly>".
								      "<input name=cuentacargo".$li_fila."  type=hidden id=cuentacargo".$li_fila."  value='".$ls_spg_cuenta."'>".
								      "<input name=codprogcargo".$li_fila."  type=hidden id=codprogcargo".$li_fila."  value='".$ls_codprog."'>".
									  "<input name=estclacargo".$li_fila."  type=hidden id=estclacargo".$li_fila."  value='".$ls_estcla."'>".
									  "<input name=formulacargo".$li_fila." type=hidden id=formulacargo".$li_fila." value='".$ls_formula."'>";
		      //$lo_object[$li_fila][7]="<a href=javascript:ue_delete_cargos('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($li_fila-1,$lo_title,$lo_object,840,"Otros Cr&eacute;ditos","gridcreditos");
		unset($io_registro_orden);		
		unset($io_dts_cargos);
		print "<table width='840' height='22' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "        <tr>";
		print "          <td width='175' height='22' align='right'><div align='left'><input name='btncrear' type='button' class='boton' id='btncerrar' value='Crear Asiento' onClick='javascript: ue_crear_asiento();'></div></td>";
		print "        </tr>";
		print "</table>";
	}// end function uf_print_creditos_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_gasto_sep($as_numsol,$ai_total,$as_tipo,$as_tipsol)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_gasto
		//		   Access: private
		//	    Arguments: as_numsol --> numero de solicitud a buscar
		//                 ai_total  ---> total de filas a imprimir de los cargos
		//                 as_tipo --> tipo de orden de compra si es de bienes ó de servicios
		//                 as_tipsol ---> tipo de la solicitud si es sep o soc 
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
		       $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_ds_cuentas=new class_datastore();
		
		// Titulos del Grid
		$lo_title[1]="Estructura Presupuestaria";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		$ls_codpro="";
		
		// Recorrido del Grid de Cuentas Presupuestarias del opener
		for ($li=1;$li<=$ai_total;$li++)
		    {
			  $ls_estcla    = trim($io_funciones_soc->uf_obtenervalor("estclapre".$li,""));
			  $ls_codestpro = trim($io_funciones_soc->uf_obtenervalor("txtprogramaticagas".$li,""));
			  $ls_codprogas = trim($io_funciones_soc->uf_obtenervalor("txtcodprogas".$li,""));
			  $ls_cuentagas = trim($io_funciones_soc->uf_obtenervalor("txtcuentagas".$li,""));
			  $ld_moncuegas = trim($io_funciones_soc->uf_obtenervalor("txtmoncuegas".$li,"0,00"));
			  $ld_moncuegas = str_replace(".","",$ld_moncuegas);
			  $ld_moncuegas = str_replace(",",".",$ld_moncuegas);							
			  if ($ls_cuentagas!="")
			     {
				   //$io_ds_cuentas->insertRow("programaticagas",$ls_programaticagas);	
				   $io_ds_cuentas->insertRow("programaticagas",$ls_codestpro);		
				   $io_ds_cuentas->insertRow("estcla",$ls_estcla);
				   $io_ds_cuentas->insertRow("codprogas",$ls_codprogas);			
				   $io_ds_cuentas->insertRow("cuentagas",$ls_cuentagas);			
				   $io_ds_cuentas->insertRow("moncuegas",$ld_moncuegas);
				   $ls_codestpro1 = substr($ls_codprogas,0,25); 
			       $ls_codestpro2 = substr($ls_codprogas,25,25); 
			       $ls_codestpro3 = substr($ls_codprogas,50,25); 
			       $ls_codestpro4 = substr($ls_codprogas,75,25); 
			       $ls_codestpro5 = substr($ls_codprogas,100,25);
				   $io_ds_cuentas->insertRow("codestpro1",$ls_codestpro1); 
				   $io_ds_cuentas->insertRow("codestpro2",$ls_codestpro2);
				   $io_ds_cuentas->insertRow("codestpro3",$ls_codestpro3);	
				   $io_ds_cuentas->insertRow("codestpro4",$ls_codestpro4);
				   $io_ds_cuentas->insertRow("codestpro5",$ls_codestpro5);				
			     }
		    }
		//buscamos la programatica y la cuenta gasto de la sep seleccionada
		require_once("sigesp_soc_c_registro_orden_compra.php");
		$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
		$io_dscuentas = $io_registro_orden->uf_load_cuentas($as_numsol,$as_tipo,$as_tipsol);
		if ($io_dscuentas!=false)
		   {
			 $li_totrow=$io_dscuentas->getRowCount("spg_cuenta");
		 	 for ($li_i=1;($li_i<=$li_totrow);$li_i++)
			     {
				   $li_monto = $io_dscuentas->data["total"][$li_i];
				   if ($li_monto>0)
				      { 
					    $ls_cuentagas  = trim($io_dscuentas->data["spg_cuenta"][$li_i]);
					    $ld_moncuegas  = $io_dscuentas->data["total"][$li_i];
						$ls_estcla     = $io_dscuentas->data["estcla"][$li_i];
						$ls_codestpro1 = trim($io_dscuentas->data["codestpro1"][$li_i]);  
						$ls_codestpro2 = trim($io_dscuentas->data["codestpro2"][$li_i]);
						$ls_codestpro3 = trim($io_dscuentas->data["codestpro3"][$li_i]);
						$ls_codestpro4 = trim($io_dscuentas->data["codestpro4"][$li_i]);
						$ls_codestpro5 = trim($io_dscuentas->data["codestpro5"][$li_i]);
						
						$ls_codestpro1com = trim($io_dscuentas->data["codestpro1"][$li_i]);
						$ls_codestpro2com = trim($io_dscuentas->data["codestpro2"][$li_i]);
						$ls_codestpro3com = trim($io_dscuentas->data["codestpro3"][$li_i]);
						$ls_codestpro4com = trim($io_dscuentas->data["codestpro4"][$li_i]);
						$ls_codestpro5com = trim($io_dscuentas->data["codestpro5"][$li_i]);
						
						$ls_codprogas  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
						
						$ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
						$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
						$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
						$ls_codestpro = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
						if ($li_estmodest==2)
						   {
                             $ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
							 $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
						     $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
							 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;						   
						   }
						elseif($li_estmodest==1) 
						   {
						     if ($ls_estcla=='P')
							    {
								  $ls_denestcla  = 'Proyecto';
								}
						     elseif($ls_estcla=='A')
							    {
								  $ls_denestcla  = 'Actividad';
								} 
						   }
					
						$io_ds_cuentas->insertRow("programaticagas",$ls_codestpro);
						$io_ds_cuentas->insertRow("estcla",$ls_estcla);
						$io_ds_cuentas->insertRow("codprogas",$ls_codprogas);
						$io_ds_cuentas->insertRow("cuentagas",$ls_cuentagas);			
						$io_ds_cuentas->insertRow("moncuegas",$ld_moncuegas);
						$io_ds_cuentas->insertRow("codestpro1",$ls_codestpro1com); 
					    $io_ds_cuentas->insertRow("codestpro2",$ls_codestpro2com);
					    $io_ds_cuentas->insertRow("codestpro3",$ls_codestpro3com);
					    $io_ds_cuentas->insertRow("codestpro4",$ls_codestpro4com);
					    $io_ds_cuentas->insertRow("codestpro5",$ls_codestpro5com);			
				}
			}
		}
		//Recorremos el datastore llenado antenriormente para vaciar la informacion al grid
		$io_ds_cuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4',
		                               '4'=>'codestpro5','5'=>'estcla','6'=>'cuentagas'),array('0'=>'moncuegas'),'moncuegas');
		$li_rows=$io_ds_cuentas->getRowCount('codestpro1');
		for($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		{
		   $ls_programaticagas = trim($io_ds_cuentas->getValue('programaticagas',$li_fila));
		   $ls_codprogas       = $io_ds_cuentas->getValue('codprogas',$li_fila);
		   $ls_cuentagas       = $io_ds_cuentas->getValue('cuentagas',$li_fila);
		   $ld_moncuegas       = number_format($io_ds_cuentas->getValue('moncuegas',$li_fila),2,",","."); 
		   $ls_estcla       = $io_ds_cuentas->getValue('estcla',$li_fila);

		   $lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." id=txtprogramaticagas".$li_fila." type=text class=sin-borde  style=text-align:center size=80 value='".$ls_programaticagas."' readonly title='".$ls_programaticagas.' - '.$ls_denestcla."'>";
		   $lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." id=txtcuentagas".$li_fila." type=text class=sin-borde  style=text-align:center size=30 value='".$ls_cuentagas."' readonly>";
		   $lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." id=txtmoncuegas".$li_fila." type=text class=sin-borde  style=text-align:right  size=30 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$ld_moncuegas."' >".
							   "<input name=txtcodprogas".$li_fila."  id=txtcodprogas".$li_fila." type=hidden value='".$ls_codprogas."'><input name=estclapre".$li_fila."  id=estclapre".$li_fila." type=hidden value='".$ls_estcla."'>";
			// $lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
		
		}
		$lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=80 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila."       type=text id=txtcuentagas".$li_fila."       class=sin-borde  style=text-align:center size=30 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila."       type=text id=txtmoncuegas".$li_fila."       class=sin-borde  style=text-align:right  size=30 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtcodprogas".$li_fila."       type=hidden id=txtcodprogas".$li_fila."  value=''><input name=estclapre".$li_fila."  id=estclapre".$li_fila." type=hidden value=''>";        
        
		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg('".$li_fila."');'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_dscuentas,$io_ds_cuentas,$io_registro_orden);
	}// end function uf_print_cuentas_gasto_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_cargo_sep($as_numsol,$ai_total,$as_cargarcargos,$as_tipo,$as_tipsol,$as_tipconpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_cargo_sep
		//		   Access: private
		//	    Arguments: as_numsol --> numero de la solicitud a buscar los detalles
		//                 ai_total  ---> total de filas a imprimir del los cargos
		//                 as_cargarcargos ---> Si cargamos los cargos ó solo pintamos
		//                 as_tipo ---> Tipo de SEP si es de bienes ó de servicios
		//	  Description: Se encarga de imprimir los cargos de una sep
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 12/05/2007						Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $la_cuentacargo,$li_loncodestpro1,$li_loncodestpro2,
		       $li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5,$li_estmodest,$ls_codestpro,$ls_denestcla;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuenta_cargos=new class_datastore();
		
		// Titulos el Grid
		$lo_title[1] = "Cr&eacute;dito";
		$lo_title[2] = "Estructura Presupuestaria";
		$lo_title[3] = "Cuenta";
		$lo_title[4] = "Monto";
		//$lo_title[5] = ""; 
		$ls_codpro   = "";
		// Recorrido del Grid de Cuentas Presupuestarias del Cargo
		for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		    {
			  $ls_codcargo        = trim($io_funciones_soc->uf_obtenervalor("txtcodcargo".$li_fila,""));
			  $ls_programaticacar = trim($io_funciones_soc->uf_obtenervalor("txtprogramaticacar".$li_fila,""));
			  $ls_cuentacar       = trim($io_funciones_soc->uf_obtenervalor("txtcuentacar".$li_fila,""));
			  $ld_moncuecar       = trim($io_funciones_soc->uf_obtenervalor("txtmoncuecar".$li_fila,"0,00"));
			  $ls_estcla          = $io_funciones_soc->uf_obtenervalor("estclacar".$li_fila,"");
			  $ld_moncuecar       = str_replace(".","",$ld_moncuecar);
			  $ld_moncuecar       = str_replace(",",".",$ld_moncuecar);	
			  $ls_codprocar       = trim($io_funciones_soc->uf_obtenervalor("txtcodprocar".$li_fila,""));
			  if (!empty($ls_cuentacar))
			     {
				   $io_dscuenta_cargos->insertRow("codcargo",$ls_codcargo);
				   $io_dscuenta_cargos->insertRow("estcla",$ls_estcla);	
				   $io_dscuenta_cargos->insertRow("programaticacar",$ls_programaticacar);			
				   $io_dscuenta_cargos->insertRow("cuentacar",$ls_cuentacar);			
				   $io_dscuenta_cargos->insertRow("moncuecar",$ld_moncuecar);	
				   $io_dscuenta_cargos->insertRow("codprocar",$ls_codprocar);
				   $ls_codestpro1 = substr($ls_codprocar,0,25); 
			       $ls_codestpro2 = substr($ls_codprocar,25,25); 
			       $ls_codestpro3 = substr($ls_codprocar,50,25); 
			       $ls_codestpro4 = substr($ls_codprocar,75,25); 
			       $ls_codestpro5 = substr($ls_codprocar,100,25);
				   $io_dscuenta_cargos->insertRow("codestpro1",$ls_codestpro1); 
				   $io_dscuenta_cargos->insertRow("codestpro2",$ls_codestpro2);
				   $io_dscuenta_cargos->insertRow("codestpro3",$ls_codestpro3);	
				   $io_dscuenta_cargos->insertRow("codestpro4",$ls_codestpro4);
				   $io_dscuenta_cargos->insertRow("codestpro5",$ls_codestpro5);		
			     }
		    }

	    if(($as_tipconpro=="O")||($as_tipconpro=="E"))
	    {
			require_once("sigesp_soc_c_registro_orden_compra.php");
			$io_registro_orden=new sigesp_soc_c_registro_orden_compra("../../");
			$rs_data = $io_registro_orden->uf_load_cuentas_cargo($as_numsol,$as_tipo,$as_tipsol);
			while ($row=$io_registro_orden->io_sql->fetch_row($rs_data))	  
		  	      {
				    $ls_codcargo   = $row["codcar"];
				    $ls_estcla     = $row["estcla"];
					$ls_codestpro1 = trim($row["codestpro1"]);
					$ls_codestpro2 = trim($row["codestpro2"]);
					$ls_codestpro3 = trim($row["codestpro3"]);
					$ls_codestpro4 = trim($row["codestpro4"]);
					$ls_codestpro5 = trim($row["codestpro5"]); 
					$ls_codprocar  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				    $ls_cuentacar  = trim($row["spg_cuenta"]);
				    $ld_moncuecar  = $row["total"];

                    $ls_codestpro1com =$ls_codestpro1;
					$ls_codestpro2com =$ls_codestpro2;
					$ls_codestpro3com =$ls_codestpro3;
					$ls_codestpro4com =$ls_codestpro4;
					$ls_codestpro5com =$ls_codestpro5;
				    
					$ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
					$ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
					$ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
					$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3; 
					
					if ($li_estmodest==2)
					   {
						 $ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
						 $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
						 $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
						 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;						   
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
				 
					$io_dscuenta_cargos->insertRow("estcla",$ls_estcla);
					$io_dscuenta_cargos->insertRow("codcargo",$ls_codcargo);			
				    $io_dscuenta_cargos->insertRow("programaticacar",$ls_codestpro);
			 	    $io_dscuenta_cargos->insertRow("cuentacar",$ls_cuentacar);			
				    $io_dscuenta_cargos->insertRow("moncuecar",$ld_moncuecar);	
				    $io_dscuenta_cargos->insertRow("codprocar",$ls_codprocar);
					$io_dscuenta_cargos->insertRow("codestpro1",$ls_codestpro1com);
					$io_dscuenta_cargos->insertRow("codestpro2",$ls_codestpro2com);
					$io_dscuenta_cargos->insertRow("codestpro3",$ls_codestpro3com);
					$io_dscuenta_cargos->insertRow("codestpro4",$ls_codestpro4com);
					$io_dscuenta_cargos->insertRow("codestpro5",$ls_codestpro5com);								
			}
		}
		//Recorremos el datastore llenado antenriormente para vaciar la informacion al grid
		$io_dscuenta_cargos->group_by(array('0'=>'codcargo','1'=>'codestpro1','2'=>'codestpro2','3'=>'codestpro3',
		                                    '4'=>'codestpro4','5'=>'codestpro5','6'=>'estcla','7'=>'cuentacar'),
									  array('0'=>'moncuecar'),'moncuecar');
		$li_rows=$io_dscuenta_cargos->getRowCount('codestpro1'); 
		for($li_fila=1;$li_fila<=$li_rows;$li_fila++)
		{ 
		    $ls_estcla          = $io_dscuenta_cargos->getValue('estcla',$li_fila);
			$ls_codcargo        = $io_dscuenta_cargos->getValue('codcargo',$li_fila);
			$ls_programaticacar = $io_dscuenta_cargos->getValue('programaticacar',$li_fila);
		    $ls_cuentacar       = $io_dscuenta_cargos->getValue('cuentacar',$li_fila);
		    $ld_moncuecar       = number_format($io_dscuenta_cargos->getValue('moncuecar',$li_fila),2,",",".");
		    $ls_codprocar       = $io_dscuenta_cargos->getValue('codprocar',$li_fila);

			$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila." type=text id=txtcodcargo".$li_fila." class=sin-borde  style=text-align:center size=12 value='".$ls_codcargo."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=75 value='".$ls_programaticacar."' readonly title='".$ls_codestpro.' - '.$ls_denestcla."'>";
			$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila." type=text id=txtcuentacar".$li_fila." class=sin-borde  style=text-align:center size=25 value='".$ls_cuentacar."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila." type=text id=txtmoncuecar".$li_fila." class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$ld_moncuecar."' readonly>".
									"<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value='".$ls_codprocar."'><input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value='".$ls_estcla."'>";
			//$lo_object[$li_fila][5]="<a href=javascript:ue_delete_cuenta_cargo('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
        }
		$lo_object[$li_fila][1]="<input name=txtcodcargo".$li_fila."        type=text id=txtcodcargo".$li_fila."        class=sin-borde  style=text-align:center size=12 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtprogramaticacar".$li_fila." type=text id=txtprogramaticacar".$li_fila." class=sin-borde  style=text-align:center size=75 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtcuentacar".$li_fila."       type=text id=txtcuentacar".$li_fila."       class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtmoncuecar".$li_fila."       type=text id=txtmoncuecar".$li_fila."       class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtcodprocar".$li_fila."  type=hidden id=txtcodprocar".$li_fila."  value=''><input name=estclacar".$li_fila."  type=hidden id=estclacar".$li_fila."  value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_cargos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Otros Cr&eacute;ditos</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,840,"Cuentas Otros Cr&eacute;ditos","gridcuentascargos");
		unset($io_dscuentas);
	}// end function uf_print_cuentas_cargo_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_sep($ad_subordcom,$ad_creordcom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: as_numsol ---> numero de la solicitud a buscar los totales
		//                 ad_subtotal ---> valor del subtotal de la sep cargada en la orden de compra
		//				   ad_cargos ---> valor total de los cargos de la sep cargada en la orden de compra
		//				   ad_total ---> total de la solicitud de pago de la sep cargada en la orden de compra
		//	  Description: Método que suma los totales de una sep cargada y los totales de una sep selecionada y los imprime
		//	   Creado Por: Ing.Yozelin Barragan
		// Fecha Creación: 12/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$ld_totordcom = 0;
		$ld_totordcom = ($ad_subordcom+$ad_creordcom);
		$ld_subordcom = number_format($ad_subordcom,2,',','.');
		$ld_creordcom = number_format($ad_creordcom,2,',','.');
		$ld_totordcom = number_format($ld_totordcom,2,',','.');
		
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
		print "          <td height='22'><input name='txtsubtotal'  type='text' class='titulo-conect' id='txtsubtotal' style='text-align:right' value='".$ld_subordcom."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><div align='right'><strong>Otros Cr&eacute;ditos&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txtcargos' type='text' class='titulo-conect' id='txtcargos' style='text-align:right' value='".$ld_creordcom."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotal' type='text' class='titulo-conect' id='txttotal' style='text-align:right' value='".$ld_totordcom."' size='30' maxlength='25' readonly align='right'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
	}// end function uf_print_total_sep
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
		//	   Creado Por:  Ing. Luis Lang
		// Fecha Creación: 17/03/2009								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid,$io_funciones_soc,$la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
			   $li_loncodestpro4,$li_loncodestpro5;
		require_once("../../shared/class_folder/class_datastore.php");
		$io_dscuentas=new class_datastore();
		
		// Titulos del Grid
		$lo_title[1]="Estructura Presupuestaria";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Monto";
		//$lo_title[4]=""; 
		$ls_codpro="";
		// Recorrido del Grid de Cuentas Presupuestarias
		if($as_tipo=="B")
		{
			for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
			{ 
				$li_moncue= $io_funciones_soc->uf_obtenervalor("txtsubtotart".$li_fila,"0,00");
				$ls_cuenta= trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
				$ls_codprogas= trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));
				$ls_estclapre= trim($io_funciones_soc->uf_obtenervalor("estcla".$li_fila,""));
				$li_moncue= str_replace(".","",$li_moncue);
				$li_moncue= str_replace(",",".",$li_moncue);	
				$ls_codestpro1= substr($ls_codprogas,0,25);
				$ls_codestpro2= substr($ls_codprogas,25,25);
				$ls_codestpro3= substr($ls_codprogas,50,25); 
				$ls_codestpro4= substr($ls_codprogas,75,25);
				$ls_codestpro5= substr($ls_codprogas,100,25); 					
				if (!empty($ls_cuenta))
				{
					$io_dscuentas->insertRow("estcla",$ls_estclapre);
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
		}
		else
		{
			for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
			{ 
				$li_moncue= $io_funciones_soc->uf_obtenervalor("txtsubtotser".$li_fila,"0,00");
				$ls_cuenta= trim($io_funciones_soc->uf_obtenervalor("txtspgcuenta".$li_fila,""));
				$ls_codprogas= trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));
				$ls_estclapre= trim($io_funciones_soc->uf_obtenervalor("estcla".$li_fila,""));
				$li_moncue= str_replace(".","",$li_moncue);
				$li_moncue= str_replace(",",".",$li_moncue);	
				$ls_codestpro1= substr($ls_codprogas,0,25);
				$ls_codestpro2= substr($ls_codprogas,25,25);
				$ls_codestpro3= substr($ls_codprogas,50,25); 
				$ls_codestpro4= substr($ls_codprogas,75,25);
				$ls_codestpro5= substr($ls_codprogas,100,25); 					
				if (!empty($ls_cuenta))
				{
					$io_dscuentas->insertRow("estcla",$ls_estclapre);
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
		}
		// Agrupamos las cuentas por programatica y cuenta
		$io_dscuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3','3'=>'codestpro4','4'=>'codestpro5',
                                      '5'=>'cuentagas','6'=>'estcla'),array('0'=>'moncuegas'),'moncuegas'); 
		$li_total=$io_dscuentas->getRowCount('codestpro1');	
		for ($li_fila=1;$li_fila<=$li_total;$li_fila++)
		    {
			  $ls_codprogas  = trim($io_dscuentas->getValue('codprogas',$li_fila));
			  $ls_cuenta     = trim($io_dscuentas->getValue('cuentagas',$li_fila));
			  $li_moncue     = number_format($io_dscuentas->getValue('moncuegas',$li_fila),2,",",".");
		      $ls_estcla     = $io_dscuentas->getValue('estcla',$li_fila);
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
			  if (!empty($ls_cuenta))
			     {
				   $lo_object[$li_fila][1]="<input name=txtprogramaticagas".$li_fila." type=text id=txtprogramaticagas".$li_fila." class=sin-borde  style=text-align:center size=80 value='".$ls_codestpro."' readonly>";
				   $lo_object[$li_fila][2]="<input name=txtcuentagas".$li_fila." type=text id=txtcuentagas".$li_fila." class=sin-borde  style=text-align:center size=30 value='".$ls_cuenta."' readonly>";
				   $lo_object[$li_fila][3]="<input name=txtmoncuegas".$li_fila." type=text id=txtmoncuegas".$li_fila." class=sin-borde  style=text-align:right  size=30 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' readonly>".
				                           "<input name=txtcodprogas".$li_fila."  type=hidden id=txtcodprogas".$li_fila."  value='".$ls_codprogas."'>".
										   "<input name=estclapre".$li_fila."  id=estclapre".$li_fila." type=hidden value='".$ls_estcla."'>";
				   //$lo_object[$li_fila][4]="<a href=javascript:ue_delete_cuenta_gasto('".$li_fila."','".$as_tipo."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
				   						   
				   
				 }
		}
		$ai_total=$li_total+1;
		$lo_object[$ai_total][1]="<input name=txtprogramaticagas".$ai_total." type=text id=txtprogramaticagas".$ai_total." class=sin-borde  style=text-align:center size=80 value='' readonly>";
		$lo_object[$ai_total][2]="<input name=txtcuentagas".$ai_total."       type=text id=txtcuentagas".$ai_total."       class=sin-borde  style=text-align:center size=30 value='' readonly>";
		$lo_object[$ai_total][3]="<input name=txtmoncuegas".$ai_total."       type=text id=txtmoncuegas".$ai_total."       class=sin-borde  style=text-align:right  size=30 value='' readonly>";
		$lo_object[$ai_total][4]="<input name=txtcodprogas".$ai_total."       type=hidden id=txtcodprogas".$ai_total."  value=''>".
		                         "<input name=estclapre".$ai_total."  id=estclapre".$ai_total." type=hidden value=''>";        

		print "<p>&nbsp;</p>";
		print "  <table width='840' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		//print "      <td  align='left'><a href='javascript:ue_catalogo_cuentas_spg($li_total);'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,840,"Cuentas","gridcuentas");
		unset($io_dscuentas);
	}// end function uf_print_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cierrecuentas_cargo($ai_total,$as_cargarcargos,$as_tipo,$as_tipconpro)
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
		global $io_grid, $io_funciones_soc,$la_cuentacargo,$li_estmodest,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
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
			$ls_cargo= trim($io_funciones_soc->uf_obtenervalor("txtcodcar".$li_fila,""));
			$li_moncue= $io_funciones_soc->uf_obtenervalor("txtmoncar".$li_fila,""); 
			$ls_cuenta= trim($io_funciones_soc->uf_obtenervalor("cuentacargo".$li_fila,""));
		    $ls_codpro= $io_funciones_soc->uf_obtenervalor("codprogcargo".$li_fila,"");
			$ls_estcla= $io_funciones_soc->uf_obtenervalor("estclacargo".$li_fila,"");
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
		//print_r($io_dscuentas->data);
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