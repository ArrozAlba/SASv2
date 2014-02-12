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
	//Tipo de Operacion a realizar Aprobacion/Reverso de Aprobacion.
	$ls_tipope = $io_funciones_soc->uf_obtenervalor("tipope","");
	//Tipo de Orden de Compra.
	$ls_tipordcom = $io_funciones_soc->uf_obtenervalor("tipordcom","");
    // proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	
	switch($ls_proceso)
	{
		case "BUSCAR":
		  uf_load_ordenes_compra($ls_numordcom,$ls_codpro,$ld_fecdes,$ld_fechas,$ls_tipordcom,$ls_tipope);
		break;
		
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ordenes_compra($as_numordcom,$as_codpro,$ad_fecdes,$ad_fechas,$as_tipordcom,$as_tipope)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenes_compra
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca los creditos de una solicitud y las imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funcion;

		// Titulos del Grid de Ordenes de compra.
		$lo_title[1] = "<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	
	    $lo_title[2] = "N&uacute;mero"; 
	    $lo_title[3] = "Proveedor"; 
        $lo_title[4] = "Observaci&oacute;n"; 
	    $lo_title[5] =" Tipo";
	    $lo_title[6] = "Fecha";
		$lo_title[7] = "Monto";
		if ($as_tipope=='R')
		   {
		     $lo_title[8] = "Aprobaci&oacute;n";
		   } 
		$lo_object[0]="";
		require_once("sigesp_soc_c_aprobacion_orden_compra.php");
		$io_aprobacion = new sigesp_soc_c_aprobacion_orden_compra("../../");
		$rs_data       = $io_aprobacion->uf_load_ordenes_compra($as_numordcom,$as_codpro,$ad_fecdes,$ad_fechas,$as_tipordcom,$as_tipope);
		$li_fila=0;
		
		while ($row=$io_aprobacion->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
			    $ls_numordcom = str_pad($row["numordcom"],15,0,0);
			    $ls_codpro    = trim($row["cod_pro"]);
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
				$ld_monordcom = number_format($row["montot"],2,",",".");
				$ld_fecordcom = $io_funcion->uf_formatovalidofecha($row["fecordcom"]);
				$ld_fecordcom = $io_funcion->uf_convertirfecmostrar($ld_fecordcom);
				$ld_fecaprcom = $io_funcion->uf_formatovalidofecha($row["fecaprord"]);
				$ld_fecaprcom = $io_funcion->uf_convertirfecmostrar($ld_fecaprcom);
				$ls_obsordcom = $row["obscom"];
			    
				$lo_object[$li_fila][1]="<input name=chk".$li_fila."          id=chk".$li_fila."           type=checkbox value=1 style=height:15px;width:15px><input type=hidden name=hidcodpro".$li_fila." name=hidcodpro".$li_fila." value='".$ls_codpro."'>";
			    $lo_object[$li_fila][2]="<input name=txtnumord".$li_fila."    id=txtnumord".$li_fila."     type=text class=sin-borde  size=15   style=text-align:center   value='".$ls_numordcom."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtnompro".$li_fila."    id=txtnompro".$li_fila."     type=text class=sin-borde  size=40   style=text-align:left     value='".$ls_nompro."'    readonly title='".$ls_nompro."'>";
		 	    $lo_object[$li_fila][4]="<input name=txtobsordcom".$li_fila." id=txtobsordcom".$li_fila."  type=text class=sin-borde  size=50   style=text-align:left     value='".$ls_obsordcom."' readonly title='".$ls_obsordcom."'>";
				$lo_object[$li_fila][5]="<input name=txttipordcom".$li_fila." id=txttipordcom".$li_fila."  type=text class=sin-borde  size=10   style=text-align:center   value='".$ls_tipordcom."' readonly>";
		 	    $lo_object[$li_fila][6]="<input name=txtfecordcom".$li_fila." id=txtfecordcom".$li_fila."  type=text class=sin-borde  size=10   style=text-align:center   value='".$ld_fecordcom."' readonly>";
				$lo_object[$li_fila][7]="<input name=txtmonordcom".$li_fila." id=txtmonordcom".$li_fila."  type=text class=sin-borde  size=20   style=text-align:right    value='".$ld_monordcom."' readonly>";
				if ($as_tipope=='R')
				   {
		 	         $lo_object[$li_fila][8]="<input name=txtfecaprord".$li_fila." id=txtfecaprord".$li_fila."  type=text class=sin-borde  size=12   style=text-align:center    value='".$ld_fecaprcom."' readonly>";
				   } 
		      }
		if ($li_fila>=1)
		   {
	   		 print "<p>&nbsp;</p>";
			 $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,800,"Ordenes de Compra","gridcompras",250);
		   }
		unset($io_aprobacion);		
	}// end function uf_load_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
?>