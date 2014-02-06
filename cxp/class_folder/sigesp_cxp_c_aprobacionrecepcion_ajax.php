<?php
	session_start();  
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("sigesp_cxp_c_aprobacionrecepcion.php");
	$io_aprobacion=new sigesp_cxp_c_aprobacionrecepcion('../../');
	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	// numero de sep
	$ls_numsol=$io_funciones_cxp->uf_obtenervalor("numsol","");
	// fecha(registro) de inicio de busqueda
	$ld_fecregdes=$io_funciones_cxp->uf_obtenervalor("fecregdes","");
	// fecha(registro) de fin de busqueda
	$ld_fecreghas=$io_funciones_cxp->uf_obtenervalor("fecreghas","");
	// codigo de proveedor/beneficiario
	$ls_proben=$io_funciones_cxp->uf_obtenervalor("proben","");
	// tipo proveedor/beneficiario
	$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben","");
	// tipo de operacion aprobacion/reverso
	$ls_tipooperacion=$io_funciones_cxp->uf_obtenervalor("tipooperacion","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			uf_print_solicitudes($ls_numsol,$ld_fecregdes,$ld_fecreghas,$ls_tipproben,$ls_proben,$ls_tipooperacion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_solicitudes($as_numsol,$ad_fecregdes,$ad_fecreghas,$as_tipproben,$as_proben,$as_tipooperacion)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_solicitudes
		//		   Access: private
		//		 Argument: as_numsol        // Numero de la solicitud de orden de Pago
		//                 ad_fecregdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipproben     // Tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Método que impirme el grid de las recepciones a ser aprobadas o para reversar la aprovaciòn
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("class_funciones_cxp.php");
		$io_cxp= new class_funciones_cxp();
		global $io_grid, $io_funciones_cxp, $io_funciones, $io_aprobacion, $io_mensajes;
		// Titulos del Grid de Solicitudes
		$lo_title[1]="<input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";
		$lo_title[2]="Numero de Recepcion";
		$lo_title[3]="Fecha Registro";
		$lo_title[4]="Proveedor / Beneficiario";
		$lo_title[5]="Estatus de Aprobacion";
		$lo_title[6]="Monto";
		$ad_fecregdes=$io_funciones->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$io_funciones->uf_convertirdatetobd($ad_fecreghas);
		$as_numsol="%".$as_numsol."%";
		$as_proben="%".$as_proben."%";
		$rs_datasol=$io_aprobacion->uf_load_recepciones($as_numsol,$ad_fecregdes,$ad_fecreghas,$as_tipproben,$as_proben,$as_tipooperacion);
		$li_fila=0;
		if($rs_datasol!=false)
		{
			while($row=$io_aprobacion->io_sql->fetch_row($rs_datasol))
			{
				$lb_imprimir=true;
				$ls_numrecdoc=$row["numrecdoc"];
				$ld_fecregdoc=date("Y-m-d",strtotime($row["fecregdoc"]));
				$ls_estaprord=$row["estaprord"];
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedben=$row["ced_bene"];
				$li_rowspg=$row["rowspg"];
				$li_rowscg=$row["rowscg"];
				$ls_proben=utf8_encode($row["nombre"]);
				$li_montotdoc=number_format($row["montotdoc"],2,',','.');
				if($ls_estaprord==0)
				{
					$ls_estatus="No Aprobada";
				}
				else
				{
					$ls_estatus="Aprobada";
				}
				if($li_rowspg>=1)
				{
					$lb_valido=$io_cxp->uf_verificar_cierre_spg("../../",$ls_estciespg);
					if($ls_estciespg=="1")
					{
						$lb_imprimir=false;
					}
				}
				if($li_rowscg>=1)
				{
					$lb_valido=$io_cxp->uf_verificar_cierre_scg("../../",$ls_estciescg);
					if($ls_estciescg=="1")
					{
						$lb_imprimir=false;
					}
				}
				$ld_fecregdoc=$io_funciones->uf_convertirfecmostrar($ld_fecregdoc);
				if($lb_imprimir)
				{
					$li_fila=$li_fila + 1;
					$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion".$li_fila.">";
					$lo_object[$li_fila][2]="<input type=text name=txtnumrecdoc".$li_fila." id=txtnumrecdoc".$li_fila." class=sin-borde style=text-align:center size=20 value='".$ls_numrecdoc."' readonly>";
					$lo_object[$li_fila][3]="<input type=text name=txtfecregdoc".$li_fila." id=txtfecregdoc".$li_fila." class=sin-borde style=text-align:left   size=15 value='".$ld_fecregdoc."' readonly>"; 
					$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    id=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 value='".$ls_proben."'    readonly>"; 
					$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    id=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 value='".$ls_estatus."'   readonly>";
					$lo_object[$li_fila][6]="<input type=text name=txtmontotdoc".$li_fila." id=txtmontotdoc".$li_fila." class=sin-borde style=text-align:right  size=20 value='".$li_montotdoc."' readonly>".
											"<input type=hidden name=txtcodtipdoc".$li_fila." id=txtcodtipdoc".$li_fila." class=sin-borde style=text-align:right  size=20 value='".$ls_codtipdoc."' readonly>".
											"<input type=hidden name=txtcodpro".$li_fila."  id=txtcodpro".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$ls_codpro."' readonly>".
											"<input type=hidden name=txtcedben".$li_fila."  id=txtcedben".$li_fila."    class=sin-borde style=text-align:right  size=20 value='".$ls_cedben."' readonly>";
				}
			}
		}
/*		if(($li_rowspg>1)&&($ls_estciespg=="1"))
		{
			$io_aprobacion->io_mensajes->message("Esta procesado el cierre presupuestario");
		}
		if(($li_rowscg>1)&&($ls_estciescg=="1"))
		{
			$io_aprobacion->io_mensajes->message("Esta procesado el cierre contable");
		}
		
*/		if($li_fila==0)
		{
			$io_aprobacion->io_mensajes->message("No se encontraron resultados");
			$li_fila=1;
			$lo_object[$li_fila][1]="<input type=checkbox name=chkaprobacion value=1 disabled/>";
			$lo_object[$li_fila][2]="<input type=text name=txtnumrecdoc".$li_fila." class=sin-borde style=text-align:center size=20 readonly>";
			$lo_object[$li_fila][3]="<input type=text name=txtfecregdoc".$li_fila." class=sin-borde style=text-align:left   size=15 readonly>"; 
			$lo_object[$li_fila][4]="<input type=text name=txtproben".$li_fila."    class=sin-borde style=text-align:left   size=35 readonly>"; 
			$lo_object[$li_fila][5]="<input type=text name=txtestapr".$li_fila."    class=sin-borde style=text-align:left   size=20 readonly>";
			$lo_object[$li_fila][6]="<input type=text name=txtmontotdoc".$li_fila." class=sin-borde style=text-align:right  size=20 readonly>";
									"<input type=hidden name=txtcodtipdoc".$li_fila." class=sin-borde style=text-align:right  size=20 readonly>".
									"<input type=hidden name=txtcodpro".$li_fila."  class=sin-borde style=text-align:right  size=20 readonly>".
									"<input type=hidden name=txtcedben".$li_fila."  class=sin-borde style=text-align:right  size=20 readonly>";
		}

		$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Recepciones de Documentos","gridsolicitudes");
	}// end function uf_print_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
