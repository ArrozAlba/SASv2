<?php
	session_start(); 
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funcion = new class_funciones();
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	//Número de la Orden de Compra.
	$ls_numordcom = $io_funciones_soc->uf_obtenervalor("numordcom","");
	//Código del Proveedor Asociado a la Orden de Compra.
	$ls_codpro = $io_funciones_soc->uf_obtenervalor("codpro","");
	//Fecha a partir del cual realizaremos la busqueda.
	$ld_fecdes = $io_funciones_soc->uf_obtenervalor("fecdes","");
	//Fecha hasta el cual realizaremos la busqueda.
	$ld_fechas = $io_funciones_soc->uf_obtenervalor("fechas","");
    // proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	
	switch($ls_proceso)
	{
		case "BUSCAR":
		  uf_load_ordenes_compra($ls_numordcom,$ld_fecdes,$ld_fechas,$ls_codpro);
		break;
	}	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ordenes_compra($as_numordcom,$ad_fecdes,$ad_fechas,$as_codpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenes_compra
		//		   Access: private
		//	    Arguments: 
		//   $as_numordcom //Número de la Orden de Compra
		//      $ad_fecdes //Fecha a partir del cual realizaremos la búsqueda de las Ordenes de Compra.
		//      $ad_fechas //Fecha a hasta el cual realizaremos la búsqueda de las Ordenes de Compra.
		//      $as_codpro //Código del Proveedor asociado a las Ordenes de Compra.
		//	  Description: Método que busca las Ordenes de compra que pueden ser Anuladas.
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 09/06/2007								Fecha Última Modificación : 09/06/2007. 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funcion;

		// Titulos del Grid de Ordenes de compra.
	    $lo_title[1]  = "<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	
		$lo_title[2]  = "N&uacute;mero"; 
	    $lo_title[3]  = "Proveedor"; 
	    $lo_title[4]  = "Observaci&oacute;n";  
        $lo_title[5]  = "Tipo"; 
	    $lo_title[6]  = "Fecha";  
	    $lo_title[7]  = "Monto";  
		$lo_object[0] = "";
		require_once("sigesp_soc_c_anulacion_orden_compra.php");
		$io_anulacion = new sigesp_soc_c_anulacion_orden_compra("../../");
		$rs_data      = $io_anulacion->uf_load_ordenes_compra($as_numordcom,$ad_fecdes,$ad_fechas,$as_codpro);
		$li_fila=0;
		
		while ($row=$io_anulacion->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
			    $ls_numordcom = str_pad($row["numordcom"],15,0,0);
			    $ls_codpro    = str_pad($row["cod_pro"],10,0,0);
				$ls_nompro    = $row["nompro"];
				$ls_tipordcom = $row["estcondat"];
				if ($ls_tipordcom=='B')
				   {
				     $ls_tipordcom = 'Bienes';
				   }
				elseif($ls_tipordcom=='S')
				   {
				     $ls_tipordcom = 'Servicios';
				   }
				$ls_numanacot = trim($row["numanacot"]);   
				$ld_monordcom = number_format($row["montot"],2,",",".");
				$ld_fecordcom = $io_funcion->uf_convertirfecmostrar($row["fecordcom"]);
				$ls_obsordcom = $row["obscom"];
			    
				$lo_object[$li_fila][1]="<input name=chk".$li_fila."          id=chk".$li_fila."           type=checkbox value=1 style=height:15px;width:15px><input type=hidden name=hidcodpro".$li_fila." name=hidcodpro".$li_fila." value='".$ls_codpro."'>";
			    $lo_object[$li_fila][2]="<input name=txtnumord".$li_fila."    id=txtnumord".$li_fila."     type=text class=sin-borde  size=15  style=text-align:center   value='".$ls_numordcom."' readonly><input type=hidden name=hidnumanacot".$li_fila." name=hidnumanacot".$li_fila." value='".$ls_numanacot."'>";
				$lo_object[$li_fila][3]="<input name=txtnompro".$li_fila."    id=txtnompro".$li_fila."     type=text class=sin-borde  size=30  style=text-align:left     value='".$ls_nompro."'    title='".$ls_nompro."'    readonly>";
		 	    $lo_object[$li_fila][4]="<input name=txtobsordcom".$li_fila." id=txtobsordcom".$li_fila."  type=text class=sin-borde  size=30  style=text-align:left     value='".$ls_obsordcom."' title='".$ls_obsordcom."' readonly>";
				$lo_object[$li_fila][5]="<input name=txttipordcom".$li_fila." id=txttipordcom".$li_fila."  type=text class=sin-borde  size=8   style=text-align:center   value='".$ls_tipordcom."' readonly>";
		 	    $lo_object[$li_fila][6]="<input name=txtfecordcom".$li_fila." id=txtfecordcom".$li_fila."  type=text class=sin-borde  size=8   style=text-align:center   value='".$ld_fecordcom."' readonly>";
				$lo_object[$li_fila][7]="<input name=txtmonordcom".$li_fila." id=txtmonordcom".$li_fila."  type=text class=sin-borde  size=17  style=text-align:right    value='".$ld_monordcom."' readonly>";
		      }
		if ($li_fila>=1)
		   {
	   		 print "<p>&nbsp;</p>";
			 $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,785,"Ordenes de Compra","gridcompras",120);
		   }
		unset($io_anulacion);		
	}// end function uf_load_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
?>