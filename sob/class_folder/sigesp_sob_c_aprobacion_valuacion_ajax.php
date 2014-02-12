<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_sob.php");
	$io_funciones_sob=new class_funciones_sob();
	require_once("sigesp_sob_c_aprobacion_valuacion.php");
	$io_aprobacion=new sigesp_sob_c_aprobacion_valuacion('../../');
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();

	// proceso a ejecutar
	$ls_proceso=$io_funciones_sob->uf_obtenervalor("proceso","");
	// numero de contrato
	$ls_codval=$io_funciones_sob->uf_obtenervalor("codval","");
	// fecha(registro) de inicio de busqueda
	$ld_fechades=$io_funciones_sob->uf_obtenervalor("fechades","");
	// fecha(registro) de fin de busqueda
	$ld_fechahas=$io_funciones_sob->uf_obtenervalor("fechahas","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_sob->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			$ls_titulo="Valuacion";
			uf_print_valuacion($ls_codval,$ld_fechades,$ld_fechahas,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_valuacion($as_codval,$ad_fechades,$ad_fechahas,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_valuacion
		//		   Access: private
		//		 Argument: as_codval        // Còdigo de valuación
		//                 ad_fechades     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fechahas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Método que imprime el grid de las valuaciones a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sob, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Valuación";
		$lo_title[3]="Contrato";
		$lo_title[4]="Asignación";
		$lo_title[5]="Estatus de Aprobacion";
		$lo_title[6]="Monto";
		$ad_fechades=$io_funciones->uf_convertirdatetobd($ad_fechades);
		$ad_fechahas=$io_funciones->uf_convertirdatetobd($ad_fechahas);
		$as_codval="%".$as_codval."%";
		if($io_aprobacion->uf_load_valuacion($as_codval,$ad_fechades,$ad_fechahas,$as_tipooperacion))
		{
			$li_fila=0;
			while(!$io_aprobacion->rs_data->EOF)
			{
				$li_fila++;
				$ls_codval=$io_aprobacion->rs_data->fields["codval"];
				$ls_codcon=$io_aprobacion->rs_data->fields["codcon"];
				$ls_codasi=$io_aprobacion->rs_data->fields["codasi"];
				$ld_fecha=$io_aprobacion->rs_data->fields["fecha"];
				$ls_estapr=$io_aprobacion->rs_data->fields["estapr"];
				$li_monto=number_format($io_aprobacion->rs_data->fields["montotval"],2,',','.');
				if($ls_estapr==0)
				{
					$ls_estatus="No Aprobado";
				}
				else
				{
					$ls_estatus="Aprobado";
				}
				$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
				$lo_object[$li_fila][2]="<input type=text name=txtcodval".$li_fila."    id=txtcodval".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_codval."'    readonly>".
										"<input type=hidden name=txtfecha".$li_fila."  id=txtfecha".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ld_fecha."' readonly>";
				$lo_object[$li_fila][3]="<input type=text name=txtcodcon".$li_fila."    id=txtcodcon".$li_fila."    class=sin-borde style=text-align:left   size=30 value='".$ls_codcon."' readonly>"; 
				$lo_object[$li_fila][4]="<input type=text name=txtcodasi".$li_fila."    id=txtcodasi".$li_fila."    class=sin-borde style=text-align:left   size=25 value='".$ls_codasi."'    readonly>"; 
				$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    id=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_estatus."'   readonly>";
				$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     id=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=15 value='".$li_monto."' 	  readonly>";
				$io_aprobacion->rs_data->MoveNext();
			}
			if($li_fila==0)
			{
				$io_aprobacion->io_mensajes->message("No se encontraron resultados");
				$li_fila=1;
				$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
				$lo_object[$li_fila][2]="<input type=text name=txtcodval".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
				$lo_object[$li_fila][3]="<input type=text name=txtcodcon".$li_fila."    class=sin-borde style=text-align:left   size=30 readonly>"; 
				$lo_object[$li_fila][4]="<input type=text name=txtcodasi".$li_fila."    class=sin-borde style=text-align:left   size=25 readonly>"; 
				$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
				$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=15 readonly>";
			}
	
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Valuaciones","gridvaluaciones");
		}
	}// end function uf_print_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------
?>