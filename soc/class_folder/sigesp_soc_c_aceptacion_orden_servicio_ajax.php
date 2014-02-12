<?php
	session_start(); 
	require_once("../../shared/class_folder/class_mensajes.php");
	$io_mensajes = new class_mensajes();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funcion = new class_funciones();
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	//Tipo de Operacion a realizar Aprobacion/Reverso de Aprobacion.
	$ls_tipope = $io_funciones_soc->uf_obtenervalor("tipope","");
    // proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	
	switch($ls_proceso)
	{
		case "CARGAR":
		  uf_load_ordenes_servicio($ls_tipope);
		break;
		
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ordenes_servicio($as_tipope)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenes_servicio
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 as_titulo // Titulo de bienes o servicios
		//                 as_tipo // Tipo de SEP si es de bienes ó de servicios
		//	  Description: Método que busca las Ordenes de Compra para realizar la aceptacion o reverso de aceptacion de las mismas.
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 03/06/2007								Fecha Última Modificación : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funcion, $io_mensajes;

		// Titulos del Grid de Ordenes de compra.
		$lo_title[1] = "Orden de Servicio";
		$lo_title[2] = "Proveedor";
		$lo_title[3] = "Fecha";
		$lo_title[4] = "Concepto";
		$lo_title[5] = "<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	


		$lo_object[0]="";
		require_once("sigesp_soc_c_aceptacion_orden_servicio.php");
		$io_aceptacion = new sigesp_soc_c_aceptacion_orden_servicio("../../");
		$rs_data       = $io_aceptacion->uf_load_ordenes_servicios($as_tipope);
		$li_fila       = 0;
		while ($row=$io_aceptacion->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila      = $li_fila+1;
				$ls_numordcom = str_pad($row["numordcom"],15,0,0);
				$ld_fecordcom = $row["fecordcom"];
				$ls_obscom    = $row["obscom"];
				$ls_nompro    = $row["nompro"];
				$ls_codpro    = str_pad($row["cod_pro"],10,0,0);
				$ld_fecordcom = $io_funcion->uf_convertirfecmostrar($ld_fecordcom);
				 
			    $lo_object[$li_fila][1] = "<input name=txtnumord".$li_fila."    id=txtnumord".$li_fila."     type=text class=sin-borde  size=15   style=text-align:center  value='".$ls_numordcom."' readonly>";
				$lo_object[$li_fila][2] = "<input name=txtnompro".$li_fila."    id=txtnompro".$li_fila."     type=text class=sin-borde  size=50   style=text-align:left    value='".$ls_nompro."'    readonly>";
				$lo_object[$li_fila][3] = "<input name=txtfecordser".$li_fila." id=txtfecordser".$li_fila."  type=text class=sin-borde  size=12   style=text-align:center  value='".$ld_fecordcom."' readonly maxlength=12>";
				$lo_object[$li_fila][4] = "<textarea name=txtobsser".$li_fila." class=sin-borde cols=40 rows=2 readonly>".$ls_obscom."</textarea>";
				$lo_object[$li_fila][5] = "<input name=chk".$li_fila."          id=chk".$li_fila."           type=checkbox value=1 style=height:15px;width:15px><input type=hidden name=hidcodpro".$li_fila." name=hidcodpro".$li_fila." value='".$ls_codpro."'>";
		      }
		if ($li_fila>=1)
		   {
	   		 print "<p>&nbsp;</p>";
			 $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,795,"Ordenes de Servicios","gridcompras",250);
		   }
		unset($io_aceptacion);		
	}// end function uf_load_ordenes_servicio
	//-----------------------------------------------------------------------------------------------------------------------------------
?>