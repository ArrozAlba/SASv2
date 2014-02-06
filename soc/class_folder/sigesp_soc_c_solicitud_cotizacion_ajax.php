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
	// tipo de SEP si es de BIENES ó de SERVICIOS
	$ls_tipo=$io_funciones_soc->uf_obtenervalor("tipo","-");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	// total de filas de Sep
	$li_totalsep=$io_funciones_soc->uf_obtenervalor("totalsep","1");
	// total de filas de Proveedores
	$li_totalproveedores=$io_funciones_soc->uf_obtenervalor("totalproveedores","1");
	// total de filas de Bienes
	$li_totalbienes=$io_funciones_soc->uf_obtenervalor("totalbienes","1");
	// total de filas de Servicios
	$li_totalservicios=$io_funciones_soc->uf_obtenervalor("totalservicios","1");
	//Obtenemos el numero de la solicitud de cotizacion.
	$ls_numsolcot = $io_funciones_soc->uf_obtenervalor("numsolcot","");
	//Obtenemos el numero de la SEP.
	$ls_numsep = $io_funciones_soc->uf_obtenervalor("numsep","-");
	$ls_opesep = $io_funciones_soc->uf_obtenervalor("opesep","-");
	
	$ls_titulo="";
	$la_cuentacargo[0]="";
	$li_cuenta=1;

	switch($ls_proceso)
	{
		case "LIMPIAR":
			switch($ls_tipo)
			{
				case "B": // Bienes
					$ls_titulo="Bien o Material";
					uf_print_bienes($li_totalbienes);
					uf_print_proveedores($li_totalproveedores);
					break;
				case "S": // Servicios
					$ls_titulo="Servicios";
					uf_print_servicios($li_totalservicios);
					uf_print_proveedores($li_totalproveedores);
					break;
			}
			break;
        case "AGREGARBIENES":
			$ls_titulo="Bien o Material";
			uf_print_sep($li_totalsep);
			uf_print_bienes($li_totalbienes);
			uf_print_proveedores($li_totalproveedores);
			break; 

		case "AGREGARSERVICIOS":
			$ls_titulo="Servicios";
			uf_print_sep($li_totalsep);
			uf_print_servicios($li_totalservicios);
			uf_print_proveedores($li_totalproveedores);
			break;
    
		case "AGREGARPROVEEDORES":
			$ls_titulo="Proveedores";
			uf_print_sep($li_totalsep);
			if ($ls_tipo=='B')
			   {
			     uf_print_bienes($li_totalbienes);
			   }
			elseif($ls_tipo=='S')
			   {
			     uf_print_servicios($li_totalservicios);
			   }
			uf_print_proveedores($li_totalproveedores);
			break;

		case "AGREGARSEP":
			uf_print_sep($li_totalsep);
			if ($ls_tipo=='B')
			   {
			     uf_load_bienes_sep($li_totalbienes,$ls_numsep,$ls_opesep);
			   }
			elseif($ls_tipo=='S')
			   {
			     uf_load_servicios_sep($li_totalservicios,$ls_numsep,$ls_opesep);
			   }
			uf_print_proveedores($li_totalproveedores);
			break;

		case "LOADBIENES":
			$ls_titulo="Bien o Material";
			uf_load_sep($ls_numsolcot);
			uf_load_bienes($ls_numsolcot);
			uf_load_proveedores($ls_numsolcot,"B");
			break;

		case "LOADSERVICIOS":
			$ls_titulo="Servicios";
			uf_load_sep($ls_numsolcot);
			uf_load_servicios($ls_numsolcot);
			uf_load_proveedores($ls_numsolcot,"S");
			break;

	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_proveedores
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los Bienes
		//	   Creado Por: Ing. Néstor Falcon
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Proveedores
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Nombre";
		$lo_title[3]="Direcci&oacute;n";
		$lo_title[4]="Tel&eacute;fono";
		$lo_title[5]="";
		// Recorrido de todos los Proveedores del Grid
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codpro = $io_funciones_soc->uf_obtenervalor("txtcodpro".$li_fila,"");
			$ls_nompro = $io_funciones_soc->uf_obtenervalor("txtnompro".$li_fila,"");
			$ls_dirpro = $io_funciones_soc->uf_obtenervalor("txtdirpro".$li_fila,"");
			$ls_telpro = $io_funciones_soc->uf_obtenervalor("txttelpro".$li_fila,"");
			$lo_object[$li_fila][1]="<input type=text name=txtcodpro".$li_fila."  id=txtcodpro".$li_fila."  class=sin-borde style=text-align:center size=10  value='".$ls_codpro."'  readonly>";
			$lo_object[$li_fila][2]="<input type=text name=txtnompro".$li_fila."  id=txtnompro".$li_fila."  class=sin-borde style=text-align:left   size=50  value='".$ls_nompro."'  readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtdirpro".$li_fila."  id=txtdirpro".$li_fila."  class=sin-borde style=text-align:left   size=55  value='".$ls_dirpro."'  readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txttelpro".$li_fila."  id=txttelpro".$li_fila."  class=sin-borde style=text-align:right  size=10  value='".$ls_telpro."'  readonly>"; 
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][5]="";
			}
			else
			{
				$lo_object[$li_fila][5]="<a href=javascript:ue_delete_proveedor('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogoproveedores();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Proveedor' width='20' height='20' border='0'>Agregar Proveedor</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,800,"Proveedores","gridproveedores",100);
	}// end function uf_print_proveedores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_bienes($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_bienes
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los Bienes
		//	   Creado Por: Ing. Néstor Falcon
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="";
		
		// Recorrido de todos los Bienes del Grid
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codart    = $io_funciones_soc->uf_obtenervalor("txtcodart".$li_fila,"");
			$ls_denart    = $io_funciones_soc->uf_obtenervalor("txtdenart".$li_fila,"");
			$li_canart    = $io_funciones_soc->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			$ls_numsep    = $io_funciones_soc->uf_obtenervalor("hidnumsep".$li_fila,"");
			$ls_codunieje = $io_funciones_soc->uf_obtenervalor("hidcodunieje".$li_fila,"");
			$ls_codestpro = $io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
			$ls_estcla    = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");
			
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=25  value='".$ls_codart."'  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=92  value='".$ls_denart."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][4]="";
			}
			else
			{
				$lo_object[$li_fila][4]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,800,"Detalle de Bienes","gridbienes",100);
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
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 13/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;

		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="";
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			$ls_codser    = $io_funciones_soc->uf_obtenervalor("txtcodser".$li_fila,"");
			$ls_denser    = $io_funciones_soc->uf_obtenervalor("txtdenser".$li_fila,"");
			$li_canser    = $io_funciones_soc->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			$ls_numsep    = $io_funciones_soc->uf_obtenervalor("hidnumsep".$li_fila,"");
			$ls_codunieje = $io_funciones_soc->uf_obtenervalor("hidcodunieje".$li_fila,"");
			$ls_codestpro = $io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
		    $ls_estcla    = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");


			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."    id=txtcodser".$li_fila." class=sin-borde  style=text-align:center  size=20   value='".$ls_codser."' readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."    id=txtdenser".$li_fila." class=sin-borde  style=text-align:left    size=100  value='".$ls_denser."' readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."    id=txtcanser".$li_fila." class=sin-borde  style=text-align:right   size=10   value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			if($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			{
				$lo_object[$li_fila][4]="";
			}
			else
			{
				$lo_object[$li_fila][4] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			}
		}
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,800,"Detalle de Servicios","gridservicios",100);
	}// end function uf_print_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numsolcot)
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
		$lo_title[4]="";
		require_once("sigesp_soc_c_solicitud_cotizacion.php");
		$io_solicitud=new sigesp_soc_c_solicitud_cotizacion("../../");
		$rs_data = $io_solicitud->uf_load_bienes($as_numsolcot);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codart     = trim($row["codart"]);
			$ls_denart     = $row["denart"];
			$li_canart     = number_format($row["canart"],2,',','.');
			$ls_numsep     = trim($row["numsep"]);
			$ls_codunieje  = trim($row["coduniadm"]);
			$ls_codestpro1 = trim($row["codestpro1"]);
			$ls_codestpro2 = trim($row["codestpro2"]);
			$ls_codestpro3 = trim($row["codestpro3"]);
			$ls_codestpro4 = trim($row["codestpro4"]);
			$ls_codestpro5 = trim($row["codestpro5"]);
			$ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_estcla     = $row["estcla"];
			
			$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=25  value='".$ls_codart."'  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila."  value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=92  value='".$ls_denart."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			$lo_object[$li_fila][4]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde  style=text-align:center  size=25  value=''  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value=''><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde  style=text-align:left    size=92  value=''  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde  style=text-align:right   size=15  value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
		$lo_object[$li_fila][4]="";
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		unset($io_solicitud);
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Detalle de Bienes","gridbienes",100);
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsolcot)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los servicios de la solicitud y los imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		// Titulos del Grid de Servicios
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="";
		require_once("sigesp_soc_c_solicitud_cotizacion.php");
		$io_solicitud=new sigesp_soc_c_solicitud_cotizacion("../../");
		$rs_data = $io_solicitud->uf_load_servicios($as_numsolcot);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila++;
			$ls_codser     = trim($row["codser"]);
			$ls_denser     = $row["denser"];
			$ls_numsep     = trim($row["numsep"]);
			$ls_codunieje  = trim($row["coduniadm"]);
			$ls_codestpro1 = trim($row["codestpro1"]);
			$ls_codestpro2 = trim($row["codestpro2"]);
			$ls_codestpro3 = trim($row["codestpro3"]);
			$ls_codestpro4 = trim($row["codestpro4"]);
			$ls_codestpro5 = trim($row["codestpro5"]);
			$ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_estcla     = $row["estcla"];
			$li_canser     = number_format($row["canser"],2,',','.');

			$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde  style=text-align:center  size=20   value='".$ls_codser."' readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde  style=text-align:left    size=100  value='".$ls_denser."' readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde  style=text-align:right   size=10   value='".$li_canser."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			$lo_object[$li_fila][4]="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde  style=text-align:center  size=20   value='' readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila."  value=''><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde  style=text-align:left    size=100  value='' readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde  style=text-align:right   size=10   value='' onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
		$lo_object[$li_fila][4] ="";
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print "		<td height='22' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Detalle de Servicios","gridservicios",100);
		unset($io_solicitud);
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proveedores($as_numsolcot,$as_tipsolcot)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_proveedores
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los proveedores de la solicitud de cotizacion y los imprime.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 11/05/2007								Fecha Última Modificación : 11/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;//, $io_funciones_soc;
		
		// Titulos del Grid de Proveedores
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Nombre";
		$lo_title[3]="Direcci&oacute;n";
		$lo_title[4]="Tel&eacute;fono";
		$lo_title[5]="";
		require_once("sigesp_soc_c_solicitud_cotizacion.php");
		$io_solicitud=new sigesp_soc_c_solicitud_cotizacion("../../");
		$rs_data = $io_solicitud->uf_load_proveedores($as_numsolcot,$as_tipsolcot);
		$li_fila=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila   = $li_fila+1;
			$ls_codpro = $row["cod_pro"];
			$ls_nompro = $row["nompro"];
			$ls_dirpro = $row["dirpro"];
			$ls_telpro = $row["telpro"];

			$lo_object[$li_fila][1] = "<input type=text name=txtcodpro".$li_fila."  id=txtcodpro".$li_fila."  class=sin-borde style=text-align:center size=10  value='".$ls_codpro."'  readonly>";
			$lo_object[$li_fila][2] = "<input type=text name=txtnompro".$li_fila."  id=txtnompro".$li_fila."  class=sin-borde style=text-align:left   size=50  value='".$ls_nompro."'  readonly>";
			$lo_object[$li_fila][3] = "<input type=text name=txtdirpro".$li_fila."  id=txtdirpro".$li_fila."  class=sin-borde style=text-align:left   size=55  value='".$ls_dirpro."'  readonly>"; 
			$lo_object[$li_fila][4] = "<input type=text name=txttelpro".$li_fila."  id=txttelpro".$li_fila."  class=sin-borde style=text-align:right  size=10  value='".$ls_telpro."'  readonly>"; 
			$lo_object[$li_fila][5] = "<a href=javascript:ue_delete_proveedor('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1] = "<input type=text name=txtcodpro".$li_fila."  id=txtcodpro".$li_fila."  class=sin-borde style=text-align:center size=10  value=''  readonly>";
		$lo_object[$li_fila][2] = "<input type=text name=txtnompro".$li_fila."  id=txtnompro".$li_fila."  class=sin-borde style=text-align:left   size=50  value=''  readonly>";
		$lo_object[$li_fila][3] = "<input type=text name=txtdirpro".$li_fila."  id=txtdirpro".$li_fila."  class=sin-borde style=text-align:left   size=55  value=''  readonly>"; 
		$lo_object[$li_fila][4] = "<input type=text name=txttelpro".$li_fila."  id=txttelpro".$li_fila."  class=sin-borde style=text-align:right  size=10  value=''  readonly>"; 
		$lo_object[$li_fila][5] = "";
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogoproveedores();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Proveedor' width='20' height='20' border='0'>Agregar Proveedor</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Proveedores","gridproveedores",100);
	}// end function uf_load_proveedores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sep($as_numsolcot)
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

		// Titulos del Grid de Proveedores
		$lo_title[1]="Nro Solicitud";
		$lo_title[2]="Descripci&oacute;n";
		$lo_title[3]="Monto";
		$lo_title[4]="";
		$lo_object[0]="";
		require_once("sigesp_soc_c_solicitud_cotizacion.php");
		$io_solicitud = new sigesp_soc_c_solicitud_cotizacion("../../");
		$rs_data      = $io_solicitud->uf_load_sep_solcot($as_numsolcot);
		$li_fila=0;

		while ($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
			    $ls_numsep    = $row["numsep"];
			    $ls_densep    = $row["densep"];
			    $ld_monsep    = number_format($row["monsep"],2,",",".");
				$ls_unieje    = $row["codunieje"];
				$ls_denuni    = $row["denuniadm"];
				$ls_estcla    = $row["estcla"];
				$ls_codestpro = trim($row["codestpro1"]).trim($row["codestpro2"]).trim($row["codestpro3"]).trim($row["codestpro4"]).trim($row["codestpro5"]);
				
			    $lo_object[$li_fila][1] = "<input name=txtnumsep".$li_fila." id=txtnumsep".$li_fila."  type=text class=sin-borde  size=20   style=text-align:center  value='".$ls_numsep."' readonly><input type=hidden name=txtunieje".$li_fila." id=txtunieje".$li_fila."  value='".$ls_unieje."'>";
			    $lo_object[$li_fila][2] = "<input name=txtdensep".$li_fila." id=txtdensep".$li_fila."  type=text class=sin-borde  size=85   style=text-align:left    value='".$ls_densep."' readonly><input type=hidden name=txtdenuni".$li_fila." id=txtdenuni".$li_fila."  value='".$ls_denuni."'>";
		 	    $lo_object[$li_fila][3] = "<input name=txtmonsep".$li_fila." id=txtmonsep".$li_fila."  type=text class=sin-borde  size=25   style=text-align:right   value='".$ld_monsep."' readonly><input type=hidden name=codestprosep".$li_fila." id=codestprosep".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estclasep".$li_fila." id=estclasep".$li_fila." value='".$ls_estcla."'>";
			    $lo_object[$li_fila][4] = "<a href=javascript:ue_delete_sep('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		      }
		$li_fila++;
		$lo_object[$li_fila][1]="<input type=text name=txtnumsep".$li_fila."  id=txtnumsep".$li_fila."  class=sin-borde  style=text-align:center  size=20  value='' readonly><input type=hidden name=txtunieje".$li_fila." id=txtunieje".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdensep".$li_fila."  id=txtdensep".$li_fila."  class=sin-borde  style=text-align:left    size=85  value='' readonly><input type=hidden name=txtdenuni".$li_fila." id=txtdenuni".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtmonsep".$li_fila."  id=txtmonsep".$li_fila."  class=sin-borde  style=text-align:right   size=25  value='' onKeyPress=return(ue_formatonumero(this,'.',',',event));><input type=hidden name=codestprosep".$li_fila." id=codestprosep".$li_fila." value=''><input type=hidden name=estclasep".$li_fila." id=estclasep".$li_fila." value=''>"; 
		$lo_object[$li_fila][4] ="";
		if ($li_fila>1)
		   {
	   		 print "<p>&nbsp;</p>";
			 $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Solicitudes de Ejecuci&oacute;n Presupuestaria","gridsep",100);
		   }
		unset($io_solicitud);		
	}// end function uf_load_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes_sep($ai_total,$as_numsep,$as_opesep)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes_sep
		//		   Access: private
		//	    Arguments: ai_total
		//	  Description: Método que busca los creditos de una solicitud y las imprime
		//	   Creado Por: Ing.Néstor Falcon
		// Fecha Creación: 15/05/2007								Fecha Última Modificación : 15/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Cantidad";
		$lo_title[4]="";

		require_once("sigesp_soc_c_solicitud_cotizacion.php");
		$io_solicitud = new sigesp_soc_c_solicitud_cotizacion("../../");
		for ($li_fila=1;$li_fila<$ai_total;$li_fila++)
		    {
			  $ls_codart    = trim($io_funciones_soc->uf_obtenervalor("txtcodart".$li_fila,""));
			  $ls_denart    = $io_funciones_soc->uf_obtenervalor("txtdenart".$li_fila,"");
			  $ld_canart    = $io_funciones_soc->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			  $ld_canart    = number_format($ld_canart,2,',','.');
			  $ls_numsep    = trim($io_funciones_soc->uf_obtenervalor("hidnumsep".$li_fila,""));
			  $ls_codunieje = trim($io_funciones_soc->uf_obtenervalor("hidcodunieje".$li_fila,""));
			  $ls_codestpro = trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));	
              $ls_estcla    = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");

			  $lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=25  value='".$ls_codart."'  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			  $lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=92  value='".$ls_denart."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			  $lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			  $lo_object[$li_fila][4]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		    }
		if (!empty($as_numsep) && ($as_opesep!="DELETE"))
		   {
			 $rs_data = $io_solicitud->uf_load_bienes_sep($as_numsep);
			 while ($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
				   {
					 $ls_codart = trim($row["codart"]); 
					 $ls_denart = $row["denart"];
					 $ls_unidad = $row["unidad"];//Mayor o Detal
					 $ld_uniart = $row["uniart"];//Unidad de Medida.
					 $ld_canart = $row["canart"];
					 if ($ls_unidad=='M')
					    { 
						  $ld_canart = ($ld_canart*$ld_uniart);
						}
					 $ld_canart    = number_format($ld_canart,2,',','.');
					 $ls_codunieje = trim($row["coduniadm"]);
					 $ls_codestpro = trim($row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"]);
					 $ls_estcla    = trim($row["estcla"]);

					 $lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=25  value='".$ls_codart."'  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value='".$as_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
					 $lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=92  value='".$ls_denart."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
					 $lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
					 $lo_object[$li_fila][4]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					 $li_fila++;
				   }
		   }
		$lo_object[$li_fila][1]="<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=25  value=''  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value=''><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value=''>";
		$lo_object[$li_fila][2]="<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=92  value=''  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		$lo_object[$li_fila][3]="<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
		$lo_object[$li_fila][4]="";
		print "<p>&nbsp;</p>";
		print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogobienes();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Bienes' width='20' height='20' border='0'>Agregar Detalle Bienes</a></td>";
		print "    </tr>";
		print "  </table>";
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Detalle de Bienes","gridbienes",100);
		unset($io_solicitud);		
	}// end function uf_load_bienes_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios_sep($ai_total,$as_numsep,$as_opesep)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios_sep
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca los creditos de una solicitud y las imprime
		//	   Creado Por: Ing.Néstor Falcon
		// Fecha Creación: 15/05/2007								Fecha Última Modificación : 15/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	global $io_grid, $io_funciones_soc;
		
		 // Titulos del Grid de Bienes
		 $lo_title[1] = "C&oacute;digo";
		 $lo_title[2] = "Denominaci&oacute;n";
		 $lo_title[3] = "Cantidad";
		 $lo_title[4] = "";

		 require_once("sigesp_soc_c_solicitud_cotizacion.php");
		 $io_solicitud = new sigesp_soc_c_solicitud_cotizacion("../../");
  	     for ($li_fila=1;$li_fila<$ai_total;$li_fila++)
			 {
			   $ls_codser    = trim($io_funciones_soc->uf_obtenervalor("txtcodser".$li_fila,""));
			   $ls_denser    = $io_funciones_soc->uf_obtenervalor("txtdenser".$li_fila,"");
			   $li_canser    = $io_funciones_soc->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			   $ls_numsep    = trim($io_funciones_soc->uf_obtenervalor("hidnumsep".$li_fila,""));
			   $ls_codunieje = trim($io_funciones_soc->uf_obtenervalor("hidcodunieje".$li_fila,""));
			   $ls_codestpro = trim($io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,""));	
               $ls_estcla    = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");

			   $lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20   value='".$ls_codser."'  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila."  value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
			   $lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=100  value='".$ls_denser."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
			   $lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=10   value='".$li_canser."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
			   $lo_object[$li_fila][4]="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			 }
		  if (!empty($as_numsep) && ($as_opesep!="DELETE"))
		     {
		       $rs_data = $io_solicitud->uf_load_servicios_sep($as_numsep);
			   while ($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
				     {
					   $ls_codser    = trim($row["codser"]); 
					   $ls_denser    = $row["denser"];
					   $li_canser    = number_format($row["canser"],2,',','.');
					   $ls_numsep    = trim($row["numsol"]);
					   $ls_codunieje = trim($row["coduniadm"]);
					   $ls_codestpro = trim($row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"]);
					   $ls_estcla    = trim($row["estcla"]);
					 
					   $lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20   value='".$ls_codser."'  readonly><input type=hidden name=hidnumsep".$li_fila."  value='".$ls_numsep."'><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value='".$ls_codunieje."'>";
					   $lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=100  value='".$ls_denser."'  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value='".$ls_estcla."'>";
					   $lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=10   value='".$li_canser."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
					   $lo_object[$li_fila][4]="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					   $li_fila++;
					 }
			 }

		 $lo_object[$li_fila][1]="<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20   value=''  readonly><input type=hidden name=hidnumsep".$li_fila." id=hidnumsep".$li_fila." value=''><input type=hidden name=hidcodunieje".$li_fila." id=hidcodunieje".$li_fila." value=''>";
		 $lo_object[$li_fila][2]="<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=100  value=''  readonly><input type=hidden name=hidcodestpro".$li_fila." id=hidcodestpro".$li_fila." value=''><input type=hidden name=estcla".$li_fila." id=estcla".$li_fila." value=''>";
		 $lo_object[$li_fila][3]="<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=10   value=''  onKeyPress=return(ue_formatonumero(this,'.',',',event));>"; 
		 $lo_object[$li_fila][4]="";
		 print "<p>&nbsp;</p>";
		 print "  <table width='800' border='0' align='center' cellpadding='0' cellspacing='0'";
		 print "    <tr>";
		 print "		<td height='22' align='left'><a href='javascript:ue_catalogoservicios();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Detalle Servicios'>Agregar Detalle Servicios</a></td>";
		 print "    </tr>";
		 print "  </table>";
		 $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Detalle de Servicios","gridservicios",100);
		 unset($io_solicitud);		
	}// end function uf_load_servicios_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_sep($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_sep
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de las Solicitudes de Ejecución Presupuestaria.
		//	   Creado Por: Ing. Néstor Falcon
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 02/05/2007.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Proveedores
		$lo_title[1]="Nro Solicitud";
		$lo_title[2]="Descripci&oacute;n";
		$lo_title[3]="Monto";
		$lo_title[4]="";
		// Recorrido de todos las Solicitudes de Ejecución Presupuestaria del Grid.
		if ($ai_total>1)
		   {
		     for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		         {
			       $ls_numsep    = $io_funciones_soc->uf_obtenervalor("txtnumsep".$li_fila,"");
				   $ls_densep    = $io_funciones_soc->uf_obtenervalor("txtdensep".$li_fila,"");
			       $ld_monsep    = $io_funciones_soc->uf_obtenervalor("txtmonsep".$li_fila,"");
			       $ls_unieje    = $io_funciones_soc->uf_obtenervalor("txtunieje".$li_fila,"");
				   $ls_denuni    = $io_funciones_soc->uf_obtenervalor("txtdenuni".$li_fila,"");
				   $ls_estcla    = $io_funciones_soc->uf_obtenervalor("estcla".$li_fila,"");
				   $ls_codestpro = $io_funciones_soc->uf_obtenervalor("hidcodestpro".$li_fila,"");
				   
				   $lo_object[$li_fila][1]="<input type=text name=txtnumsep".$li_fila."  id=txtnumsep".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_numsep."'  readonly><input type=hidden name=txtunieje".$li_fila." id=txtunieje".$li_fila."    value='".$ls_unieje."'>";
		 	       $lo_object[$li_fila][2]="<input type=text name=txtdensep".$li_fila."  id=txtdensep".$li_fila."  class=sin-borde style=text-align:left   size=85  value='".$ls_densep."'  readonly><input type=hidden name=txtdenuni".$li_fila." id=txtdenuni".$li_fila."    value='".$ls_denuni."'>";
			       $lo_object[$li_fila][3]="<input type=text name=txtmonsep".$li_fila."  id=txtmonsep".$li_fila."  class=sin-borde style=text-align:right  size=25  value='".$ld_monsep."'  readonly><input type=hidden name=codestprosep".$li_fila." id=codestprosep".$li_fila." value='".$ls_codestpro."'><input type=hidden name=estclasep".$li_fila." id=estclasep".$li_fila." value='".$ls_estcla."'>"; 
			       if ($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			          {
			 	        $lo_object[$li_fila][4]="";
			          } 
			       else
					  { 
					    $lo_object[$li_fila][4]="<a href=javascript:ue_delete_sep('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					  }
				 }
			 print "<p>&nbsp;</p>";
		     $io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,800,"Solicitudes de Ejecuci&oacute;n Presupuestaria","gridsep",100);
		   }
	}// end function uf_print_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
?>