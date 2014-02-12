<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_viaticos.php");
	$io_funciones_scv=new class_funciones_viaticos();
	require_once("sigesp_scv_c_anulacionsolicitud.php");
	$io_anulacion=new sigesp_scv_c_anulacionsolicitud('../../');
	// proceso a ejecutar
	$ls_proceso=$io_funciones_scv->uf_obtenervalor("proceso","");
	// numero de sep
	$ls_numsol=$io_funciones_scv->uf_obtenervalor("numsol","");
	// fecha(registro) de inicio de busqueda
	$ld_fecregdes=$io_funciones_scv->uf_obtenervalor("fecregdes","");
	// fecha(registro) de fin de busqueda
	$ld_fecreghas=$io_funciones_scv->uf_obtenervalor("fecreghas","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_scv->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			uf_print_solicitudes($ls_numsol,$ld_fecregdes,$ld_fecreghas,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudes($as_numsol,$ad_fecregdes,$ad_fecreghas,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes
		//		   Access: private
		//		 Argument: as_numsol        // Numero de la solicitud de orden de Pago
		//                 ad_fecregdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Método que impirme el grid de las solicitudes de viaticos para anular 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_scv, $io_funciones, $io_anulacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Numero de Solicitud";
		$lo_title[3]="Fecha Registro";
		$lo_title[4]="Ruta";
		$lo_title[5]="Mision";
		$ad_fecregdes=$io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$io_funciones->uf_convertirdatetobd($ad_fecreghas);
		$as_numsol="%".$as_numsol."%";
		$rs_datasol=$io_anulacion->uf_load_solicitudes($as_numsol,$ad_fecregdes,$ad_fecreghas,$as_tipooperacion);
		$li_fila=0;
		while($row=$io_anulacion->io_sql->fetch_row($rs_datasol))
		{
			$li_fila=$li_fila + 1;
			$ls_codsolvia=$row["codsolvia"];
			$ld_fecsolvia=$row["fecsolvia"];
			$ls_desrut=$row["desrut"];
			$ls_denmis=$row["denmis"];
			$ld_fecsolvia=$io_funciones->uf_convertirfecmostrar($ld_fecsolvia);
			$lo_object[$li_fila][1]="<input type=checkbox name=chkanulacion".$li_fila.">";
			$lo_object[$li_fila][2]="<input type=text name=txtcodsolvia".$li_fila." id=txtcodsolvia".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_codsolvia."' readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecsolvia".$li_fila." id=txtfecsolvia".$li_fila." class=sin-borde style=text-align:center   size=12 value='".$ld_fecsolvia."' readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtdesrut".$li_fila."    id=txtdesrut".$li_fila."    class=sin-borde style=text-align:left   size=35 value='".$ls_desrut."'    readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtdenmis".$li_fila."    id=txtdenmis".$li_fila."    class=sin-borde style=text-align:left   size=50 value='".$ls_denmis."'   readonly>";
		}
		if($li_fila==0)
		{
			$io_anulacion->io_mensajes->message("No se encontraron resultados");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkanulacion value=1 disabled/>";
			$lo_object[$li_fila][2]="<input type=text name=txtcodsolvia".$li_fila." class=sin-borde style=text-align:center size=20 readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecsolvia".$li_fila." class=sin-borde style=text-align:left   size=12 readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtdesrut".$li_fila."    class=sin-borde style=text-align:left   size=35 readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtdenmis".$li_fila."    class=sin-borde style=text-align:left   size=50 readonly>";
		}

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Solicitudes de Viaticos","gridsolicitudes");
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
?>