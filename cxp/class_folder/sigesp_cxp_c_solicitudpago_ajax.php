<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("../../shared/class_folder/class_datastore.php");
	$io_dscuentas=new class_datastore(); // Datastored de cuentas contables
	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	// total de filas de recepciones
	$li_totrowrecepciones=$io_funciones_cxp->uf_obtenervalor("totrowrecepciones",1);
	// total 
	$li_total=$io_funciones_cxp->uf_obtenervalor("total","0,00");
	// numero de solicitud 
	$ls_numsol=$io_funciones_cxp->uf_obtenervalor("numsol","");
	switch($ls_proceso)
	{
		case "LIMPIAR":
			uf_print_recepciones($li_totrowrecepciones,$li_total);
			break;

		case "AGREGARRECEPCIONES":
			uf_print_recepciones($li_totrowrecepciones,$li_total);
			break;
		case "LOADRECEPCIONES":
			uf_load_recepciones($ls_numsol,$li_total);
			break;
		case "CARGAR_REPORTE":
			$ls_tipoformato=$io_funciones_cxp->uf_obtenervalor("formato","");
			uf_load_formatos($ls_tipoformato);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_recepciones($ai_totrowrecepciones,$ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_recepciones
		//		   Access: private
		//	    Arguments: ai_totrowrecepciones // Total de filas de recepciones de documentos
		//				   ai_total             // Monto total
		//	  Description: Método que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_dscuentas;
		// Titulos el Grid
		$lo_title[1]="Nro. Recepción";
		$lo_title[2]="Tipo";
		$lo_title[3]="Monto";
		$lo_title[4]=" "; 
		$ls_codpro="";
		// Recorrido del Grid de Recepciones
		$li_montotal=0;
		for($li_fila=1;$li_fila<$ai_totrowrecepciones;$li_fila++)
		{
			$ls_numrecdoc=trim($io_funciones_cxp->uf_obtenervalor("txtnumrecdoc".$li_fila,""));
			$ls_codtipdoc=trim($io_funciones_cxp->uf_obtenervalor("txtcodtipdoc".$li_fila,""));
			$ls_dentipdoc=trim($io_funciones_cxp->uf_obtenervalor("txtdentipdoc".$li_fila,""));
			$li_montotdoc=trim($io_funciones_cxp->uf_obtenervalor("txtmontotdoc".$li_fila,"0,00"));
			$li_monto=str_replace(".","",$li_montotdoc);
			$li_monto=str_replace(",",".",$li_monto);
			$li_montotal=$li_montotal + $li_monto;

			$lo_object[$li_fila][1]="<input name=txtnumrecdoc".$li_fila." type=text id=txtnumrecdoc".$li_fila."   class=sin-borde  style=text-align:center size=20 value='".$ls_numrecdoc."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtdentipdoc".$li_fila." type=text id=txtdentipdoc".$li_fila."   class=sin-borde  style=text-align:center size=45 value='".$ls_dentipdoc."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtmontotdoc".$li_fila." type=text id=txtmontotdoc".$li_fila."   class=sin-borde  style=text-align:right size=25 value='".$li_montotdoc."' readonly>";
			$lo_object[$li_fila][4]="<a href=javascript:ue_delete_recepcion('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
									"<input name=txtcodtipdoc".$li_fila." type=hidden id=txtcodtipdoc".$li_fila." value='".$ls_codtipdoc."'>";
		}
		$lo_object[$ai_totrowrecepciones][1]="<input name=txtnumrecdoc".$ai_totrowrecepciones." type=text id=txtnumrecdoc".$ai_totrowrecepciones."   class=sin-borde  style=text-align:center size=20 readonly>";
		$lo_object[$ai_totrowrecepciones][2]="<input name=txtdentipdoc".$ai_totrowrecepciones." type=text id=txtdentipdoc".$ai_totrowrecepciones."   class=sin-borde  style=text-align:center size=45 readonly>";
		$lo_object[$ai_totrowrecepciones][3]="<input name=txtmontotdoc".$ai_totrowrecepciones." type=text id=txtmontotdoc".$ai_totrowrecepciones."   class=sin-borde  style=text-align:right size=25  readonly>";
		$lo_object[$ai_totrowrecepciones][4]="<a><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
											 "<input name=txtcodtipdoc".$ai_totrowrecepciones." type=hidden id=txtcodtipdoc".$ai_totrowrecepciones.">";
		if($ai_total==0)
		{
			$ai_total=$li_montotal;
		}
		print "  <table width='720' border='0' align='right' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogorecepciones();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Recepciones' width='20' height='20' border='0'>Agregar Detalle Recepciones</a></td>";
		print "    </tr>";
		print "  </table>";
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($ai_totrowrecepciones,$lo_title,$lo_object,720,"Detalle Solicitud","gridrecepciones");
		print "  <table width='720' border='0' align='right' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		print "<td  align='right' width='540'><b>Total&nbsp;&nbsp;</b></td>";
		print "<td  align='left'><input name='txtmonsol' type='text' id='txtmonsol' size='25' style='text-align:right' value='".number_format($ai_total,2,",",".")."' readonly></td>";
		print "    </tr>";
		print "  </table>";
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_recepciones($as_numsol,$ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 ai_total   // Total de la Solicitud
		//	  Description: Método que busca las recepciones de documento asociadas y las imprime
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp;

		// Titulos del Grid
		$lo_title[1]="Nro. Recepción";
		$lo_title[2]="Tipo";
		$lo_title[3]="Monto";
		$lo_title[4]=" "; 
		$lo_object[0]="";
		require_once("sigesp_cxp_c_solicitudpago.php");
		$io_solicitud=new sigesp_cxp_c_solicitudpago("../../");
		$rs_data = $io_solicitud->uf_load_recepciones($as_numsol);
		$li_fila=0;
		$ai_total=str_replace(".","",$ai_total);
		$ai_total=str_replace(",",".",$ai_total);							
		$li_montotal=0;
		while($row=$io_solicitud->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_numrecdoc=trim($row["numrecdoc"]);
			$ls_codtipdoc=trim($row["codtipdoc"]);
			$ls_dentipdoc=rtrim($row["dentipdoc"]);
			$li_montotdoc=$row["monto"];
			$li_montotal=$li_montotal + $li_montotdoc;

			$lo_object[$li_fila][1]="<input name=txtnumrecdoc".$li_fila." type=text id=txtnumrecdoc".$li_fila."   class=sin-borde  style=text-align:center size=20 value='".$ls_numrecdoc."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtdentipdoc".$li_fila." type=text id=txtdentipdoc".$li_fila."   class=sin-borde  style=text-align:center size=45 value='".$ls_dentipdoc."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtmontotdoc".$li_fila." type=text id=txtmontotdoc".$li_fila."   class=sin-borde  style=text-align:right size=25 value='".number_format($li_montotdoc,2,',','.')."' readonly>";
			$lo_object[$li_fila][4]="<a href=javascript:ue_delete_recepcion('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
									"<input name=txtcodtipdoc".$li_fila." type=hidden id=txtcodtipdoc".$li_fila." value='".$ls_codtipdoc."'>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input name=txtnumrecdoc".$li_fila." type=text id=txtnumrecdoc".$li_fila."   class=sin-borde  style=text-align:center size=20 readonly>";
		$lo_object[$li_fila][2]="<input name=txtdentipdoc".$li_fila." type=text id=txtdentipdoc".$li_fila."   class=sin-borde  style=text-align:center size=45 readonly>";
		$lo_object[$li_fila][3]="<input name=txtmontotdoc".$li_fila." type=text id=txtmontotdoc".$li_fila."   class=sin-borde  style=text-align:right size=25  readonly>";
		$lo_object[$li_fila][4]="<a><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>".
											 "<input name=txtcodtipdoc".$li_fila." type=hidden id=txtcodtipdoc".$li_fila.">";
		if($ai_total==0)
		{
			$ai_total=$li_montotal;
		}
		unset($io_solicitud);		
		print "<p>&nbsp;</p>";
		print "  <table width='720' border='0' align='right' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_catalogorecepciones();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Recepciones' width='20' height='20' border='0'>Agregar Detalle Recepciones</a></td>";
		print "    </tr>";
		print "  </table>";
		print "<p>&nbsp;</p>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Solicitud","gridrecepciones");
		print "  <table width='720' border='0' align='right' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		print "<td  align='right' width='540'><b>Total&nbsp;&nbsp;</b></td>";
		print "<td  align='left'><input name='txtmonsol' type='text' id='txtmonsol' size='25' style='text-align:right' value='".number_format($ai_total,2,",",".")."' readonly></td>";
		print "    </tr>";
		print "  </table>";
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_formatos($as_tipoformato)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_tipoformato  // Tipo de Formato a Obtener el fisico
		//	  Description: Método que busca el fisico del reporte 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/01/2009								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp;

		require_once("sigesp_cxp_c_solicitudpago.php");
		$io_recepcion=new sigesp_cxp_c_solicitudpago("../../");
		$ls_reporte=$io_recepcion->uf_load_archivoformato("CXP","REPORTE","FORMATO_SOLPAG_".$as_tipoformato,"sigesp_cxp_rfs_solicitudes.php","C");
		print "REPORTE->".$ls_reporte;
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------

?>