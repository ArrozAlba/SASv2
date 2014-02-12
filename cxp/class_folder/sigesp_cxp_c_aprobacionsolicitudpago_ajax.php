<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("sigesp_cxp_c_aprobacionsolicitudpago.php");
	$io_aprobacion=new sigesp_cxp_c_aprobacionsolicitudpago('../../');
	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	// numero de sep
	$ls_numsol=$io_funciones_cxp->uf_obtenervalor("numsol","");
	// fecha(registro) de inicio de busqueda
	$ld_fecemides=$io_funciones_cxp->uf_obtenervalor("fecemides","");
	// fecha(registro) de fin de busqueda
	$ld_fecemihas=$io_funciones_cxp->uf_obtenervalor("fecemihas","");
	// codigo de proveedor/beneficiario
	$ls_proben=$io_funciones_cxp->uf_obtenervalor("proben","");
	// tipo proveedor/beneficiario
	$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_cxp->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			uf_print_solicitudes($ls_numsol,$ld_fecemides,$ld_fecemihas,$ls_tipproben,$ls_proben,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudes($as_numsol,$ad_fecemides,$ad_fecemihas,$as_tipproben,$as_proben,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes
		//		   Access: private
		//		 Argument: as_numsol        // Numero de la solicitud de orden de Pago
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipproben     // Tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Método que impirme el grid de las solicitudes a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";
		$lo_title[2]="Numero de Solicitud";
		$lo_title[3]="Fecha Emision";
		$lo_title[4]="Proveedor / Beneficiario";
		$lo_title[5]="Estatus de Aprobacion";
		$lo_title[6]="Monto";
		$ad_fecemides=$io_funciones->uf_convertirdatetobd($ad_fecemides);
		$ad_fecemihas=$io_funciones->uf_convertirdatetobd($ad_fecemihas);
		$as_numsol="%".$as_numsol."%";
		$as_proben="%".$as_proben."%";
		$rs_datasol=$io_aprobacion->uf_load_solicitudes($as_numsol,$ad_fecemides,$ad_fecemihas,$as_tipproben,$as_proben,$as_tipooperacion);
		$li_fila=0;
		if($rs_datasol!=false)
		{
			while($row=$io_aprobacion->io_sql->fetch_row($rs_datasol))
			{
				$ls_numsol=$row["numsol"];
				$ld_fecemisol=date("Y-m-d",strtotime($row["fecemisol"]));
				$ls_estprosol=$row["estprosol"];
				$ls_estaprosol=$row["estaprosol"];
				$ls_proben=utf8_encode($row["nombre"]);
				$li_monsol=number_format($row["monsol"],2,',','.');
				if($ls_estaprosol==0)
				{
					$ls_estatus="No Aprobada";
				}
				else
				{
					$ls_estatus="Aprobada";
				}
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				$lb_valido=$io_aprobacion->uf_verificar_recepciones($ls_numsol,$lb_imprimir); 
				if($lb_imprimir)
				{
					$li_fila=$li_fila + 1;
					$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
					$lo_object[$li_fila][2]="<input type=text name=txtnumsol".$li_fila."    id=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=20 value='".$ls_numsol."'    readonly>";
					$lo_object[$li_fila][3]="<input type=text name=txtfecemisol".$li_fila." id=txtfecemisol".$li_fila." class=sin-borde style=text-align:left   size=15 value='".$ld_fecemisol."' readonly>"; 
					$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    id=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 value='".$ls_proben."'    readonly>"; 
					$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    id=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_estatus."'   readonly>";
					$lo_object[$li_fila][6]="<input type=text name=txtmonsol".$li_fila."    id=txtmonsol".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$li_monsol."' 	  readonly>";
				}
			}
		}
		if($li_fila==0)
		{
			$io_aprobacion->io_mensajes->message("No se encontraron resultados");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
			$lo_object[$li_fila][2]="<input type=text name=txtnumsol".$li_fila."    class=sin-borde style=text-align:center size=20 readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecemisol".$li_fila." class=sin-borde style=text-align:left   size=15 readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmonsol".$li_fila."     class=sin-borde style=text-align:right  size=20 readonly>";
		}

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Solicitudes de Ordenes de Pago","gridsolicitudes");
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
?>