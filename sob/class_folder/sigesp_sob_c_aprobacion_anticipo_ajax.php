<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_sob.php");
	$io_funciones_sob=new class_funciones_sob();
	require_once("sigesp_sob_c_aprobacion_anticipo.php");
	$io_aprobacion=new sigesp_sob_c_aprobacion_anticipo('../../');
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();

	// proceso a ejecutar
	$ls_proceso=$io_funciones_sob->uf_obtenervalor("proceso","");
	// numero de contrato
	$ls_codant=$io_funciones_sob->uf_obtenervalor("codant","");
	// fecha(registro) de inicio de busqueda
	$ld_fecantdes=$io_funciones_sob->uf_obtenervalor("fecantdes","");
	// fecha(registro) de fin de busqueda
	$ld_fecanthas=$io_funciones_sob->uf_obtenervalor("fecanthas","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_sob->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			$ls_titulo="Anticipos";
			uf_print_anticipo($ls_codant,$ld_fecantdes,$ld_fecanthas,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_anticipo($as_codant,$ad_fecantdes,$ad_fecanthas,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_anticipo
		//		   Access: private
		//		 Argument: as_codant        // Numero del Anticipo
		//                 ad_fecantdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecanthas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Método que imprime el grid de los contratos a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sob, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="";
		$lo_title[2]="Anticipo";
		$lo_title[3]="Contrato";
		$lo_title[4]="Asignación";
		$lo_title[5]="Estatus de Aprobacion";
		$lo_title[6]="Monto";
		$ad_fecantdes=$io_funciones->uf_convertirdatetobd($ad_fecantdes);
		$ad_fecanthas=$io_funciones->uf_convertirdatetobd($ad_fecanthas);
		$as_codant="%".$as_codant."%";
		if($io_aprobacion->uf_load_anticipo($as_codant,$ad_fecantdes,$ad_fecanthas,$as_tipooperacion))
		{
			$li_fila=0;
			while(!$io_aprobacion->rs_data->EOF)
			{
				$li_fila++;
				$ls_codant=$io_aprobacion->rs_data->fields["codant"];
				$ls_codcon=$io_aprobacion->rs_data->fields["codcon"];
				$ls_codasi=$io_aprobacion->rs_data->fields["codasi"];
				$ld_fecant=$io_aprobacion->rs_data->fields["fecant"];
				$ls_estapr=$io_aprobacion->rs_data->fields["estapr"];
				$li_monto=number_format($io_aprobacion->rs_data->fields["monto"],2,',','.');
				if($ls_estapr==0)
				{
					$ls_estatus="No Aprobado";
				}
				else
				{
					$ls_estatus="Aprobado";
				}
				$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
				$lo_object[$li_fila][2]="<input type=text name=txtcodant".$li_fila."    id=txtcodant".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_codant."'    readonly>".
										"<input type=hidden name=txtfecant".$li_fila."  id=txtfecant".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ld_fecant."' readonly>";
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
				$lo_object[$li_fila][2]="<input type=text name=txtcodant".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
				$lo_object[$li_fila][3]="<input type=text name=txtcodcon".$li_fila."    class=sin-borde style=text-align:left   size=30 readonly>"; 
				$lo_object[$li_fila][4]="<input type=text name=txtcodasi".$li_fila."    class=sin-borde style=text-align:left   size=25 readonly>"; 
				$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
				$lo_object[$li_fila][6]="<input type=text name=txtmonto".$li_fila."     class=sin-borde style=text-align:right  size=15 readonly>";
			}
	
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Anticipos","gridanticipos");
		}
	}// end function uf_print_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------
?>