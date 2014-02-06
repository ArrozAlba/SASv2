<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_sob.php");
	$io_funciones_sob=new class_funciones_sob();
	require_once("sigesp_sob_c_aprobacion_variacion.php");
	$io_aprobacion=new sigesp_sob_c_aprobacion_variacion('../../');
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();

	// proceso a ejecutar
	$ls_proceso=$io_funciones_sob->uf_obtenervalor("proceso","");
	// numero de asignación
	$ls_codvar=$io_funciones_sob->uf_obtenervalor("codvar","");
	// fecha(registro) de inicio de busqueda
	$ld_fecasides=$io_funciones_sob->uf_obtenervalor("fecasides","");
	// fecha(registro) de fin de busqueda
	$ld_fecasihas=$io_funciones_sob->uf_obtenervalor("fecasihas","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_sob->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			$ls_titulo="Variación";
			uf_print_variacion($ls_codvar,$ld_fecasides,$ld_fecasihas,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_variacion($as_codvar,$ad_fecasides,$ad_fecasihas,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_asignacion
		//		   Access: private
		//		 Argument: as_codvar        // Numero de la Asignación
		//                 ad_fecregdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Método que imprime el grid de las asignación a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sob, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Contrato";
		$lo_title[3]="Variacion";
		$lo_title[4]="Tipo Variacion";
		$lo_title[5]="Estatus de Aprobacion";
		$lo_title[6]="Monto";
		$ad_fecasides=$io_funciones->uf_convertirdatetobd($ad_fecasides);
		$ad_fecasihas=$io_funciones->uf_convertirdatetobd($ad_fecasihas);
		$as_codasi="%".$as_codasi."%";
		if($io_aprobacion->uf_load_variaciones($as_codvar,$ad_fecasides,$ad_fecasihas,$as_tipooperacion))
		{
			$li_fila=0;
			while(!$io_aprobacion->rs_data->EOF)
			{
				$li_fila++;
				$ls_codvar=$io_aprobacion->rs_data->fields["codvar"];
				$ld_fecvar=$io_aprobacion->rs_data->fields["fecvar"];
				$ls_codcon=$io_aprobacion->rs_data->fields["codcon"];
				$ls_tipvar=$io_aprobacion->rs_data->fields["tipvar"];
				$ls_estapr=$io_aprobacion->rs_data->fields["estapr"];
				$li_monto=number_format($io_aprobacion->rs_data->fields["monto"],2,',','.');
				if($ls_tipvar==1)
				{
					$ls_tipvar="AUMENTO";
				}
				else
				{
					$ls_tipvar="DISMINUCIÓN";
				}
				if($ls_estapr==0)
				{
					$ls_estatus="No Aprobada";
				}
				else
				{
					$ls_estatus="Aprobada";
				}
				$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
				$lo_object[$li_fila][2]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_codcon."'    readonly>".
										"<input type=hidden name=txtfecvar".$li_fila." id=txtfecvar".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ld_fecvar."' readonly>";
				$lo_object[$li_fila][3]="<input type=text name=txtcodvar".$li_fila."    id=txtcodvar".$li_fila." class=sin-borde style=text-align:left   size=30 value='".$ls_codvar."' readonly>"; 
				$lo_object[$li_fila][4]="<input type=text name=txttipo".$li_fila."    id=txttipo".$li_fila."    class=sin-borde style=text-align:left   size=25 value='".$ls_tipvar."'    readonly>"; 
				$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    id=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_estatus."'   readonly>";
				$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     id=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=15 value='".$li_monto."' 	  readonly>";
				$io_aprobacion->rs_data->MoveNext();
			}
			if($li_fila==0)
			{
				$io_aprobacion->io_mensajes->message("No se encontraron resultados");
				$li_fila=1;
				$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
				$lo_object[$li_fila][2]="<input type=text name=txtcodasi".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
				$lo_object[$li_fila][3]="<input type=text name=txtcodobr".$li_fila." class=sin-borde style=text-align:left   size=30 readonly>"; 
				$lo_object[$li_fila][4]="<input type=text name=txtcontratista".$li_fila."    class=sin-borde style=text-align:left   size=25 readonly>"; 
				$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
				$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=15 readonly>";
			}
	
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Asignaciones","gridasignaciones");
		}
	}// end function uf_print_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------
?>