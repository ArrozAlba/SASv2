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
	// numero del comprobante 
	$ls_numcom=$io_funciones_cxp->uf_obtenervalor("numcom","");
	$ls_codret=$io_funciones_cxp->uf_obtenervalor("codret","");
	switch($ls_proceso)
	{
		case "AGREGARCMPRET":
			uf_print_dt_cmpret($li_totrowrecepciones);
			break;
		case "LOADDETALLECMP":
			uf_load_dt_cmpret($ls_numcom,$ls_codret);
			break;
		case "AGREGARCMPRETINS":
			uf_print_dt_cmpret_ins($li_totrowrecepciones);
			break;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dt_cmpret_ins($ai_totrowrecepciones)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_dt_cmpret_ins
		//		   Access: private
		//	    Arguments: ai_totrowrecepciones // Total de filas de recepciones de documentos
		//				   ai_total             // Monto total
		//	  Description: Método que imprime el grid de las cuentas recepciones de documentos
		//	   Creado Por:
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_dscuentas;
		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		// Titulos el Grid
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Factura";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Nota Debito"; 
		$lo_title[5]="Nota Credito"; 
		$lo_title[6]="Fecha"; 
		$lo_title[7]="Total con IVA"; 
		$lo_title[8]="Total sin IVA"; 
		$lo_title[9]="Base Imponible"; 
		$lo_title[10]="Porcentaje Impuesto"; 
		$lo_title[11]="Total Impuesto";
		$lo_title[12]="Iva Retenido"; 
		$lo_title[13]="Nro. Documento";  
		$lo_title[14]="Nro. Solicitud"; 
		$lo_title[15]="Editar";
		$ls_nd="ND";
		$ls_nc="NC";
		
		// Recorrido del Grid de Recepciones
		for($li_fila=1;$li_fila<$ai_totrowrecepciones;$li_fila++)
		{
			$ls_codret=trim($io_funciones_cxp->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_funciones_cxp->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_funciones_cxp->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_funciones_cxp->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_funciones_cxp->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_funciones_cxp->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_funciones_cxp->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_funciones_cxp->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_funciones_cxp->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_funciones_cxp->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_porret=trim($io_funciones_cxp->uf_obtenervalor("txtporret".$li_fila,"0.00"));
			$ls_totimp=trim($io_funciones_cxp->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_funciones_cxp->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_numsop=trim($io_funciones_cxp->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_funciones_cxp->uf_obtenervalor("txtnumdoc".$li_fila,""));
			

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$io_funciones->uf_cerosizquierda($li_fila,10)."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila." class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."'>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila." class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' >";
			$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."  class=sin-borde  style=text-align:right size=10 value='".$ls_numnd."' onBlur= ue_validarnota('".$ls_nd."',".$li_fila.");>";
			$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."  class=sin-borde  style=text-align:right size=10 value='".$ls_numnc."' onBlur= ue_validarnota('".$ls_nc."',".$li_fila.");>";
			$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila." class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' >";
			$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(currency_Format(this,'.',',',event));  onBlur= return(ue_numeronegativo(this,".$li_fila."));>";
			$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(currency_Format(this,'.',',',event)); >";
			$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(currency_Format(this,'.',',',event)); onBlur= return(ue_numeronegativo(this,".$li_fila."));>";
			$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' readonly><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
						 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."'>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' readonly size=15><a href=javascript:ue_cat_solicitud('".$li_fila."');><img src=../shared/imagebank/tools20/buscar.gif alt=Buscar width=15 height=15 border=0 title=Buscar></a>";
			$lo_object[$li_fila][15]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."  class=sin-borde  style=text-align:right size=10 readonly";
		$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."  class=sin-borde  style=text-align:right size=10 readonly";
		$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly  value=''>";
		$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly  value=''>";
		$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly  value=''>";
		$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
		$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly size=15>";
		$lo_object[$li_fila][15]="<a><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_dt_cmpret($ai_totrowrecepciones)
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
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Factura";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Nota Debito"; 
		$lo_title[5]="Nota Credito"; 
		$lo_title[6]="Fecha"; 
		$lo_title[7]="Total con IVA"; 
		$lo_title[8]="Total sin IVA"; 
		$lo_title[9]="Base Imponible"; 
		$lo_title[10]="Porcentaje Impuesto"; 
		$lo_title[11]="Total Impuesto";
		$lo_title[12]="Iva Retenido"; 
		$lo_title[13]="Nro. Documento";  
		$lo_title[14]="Nro. Solicitud"; 
		$lo_title[15]="Editar";
		
		// Recorrido del Grid de Recepciones
		for($li_fila=1;$li_fila<$ai_totrowrecepciones;$li_fila++)
		{
			$ls_codret=trim($io_funciones_cxp->uf_obtenervalor("txtcodret".$li_fila,""));
			$ls_numope=trim($io_funciones_cxp->uf_obtenervalor("txtnumope".$li_fila,""));
			$ls_fecfac=trim($io_funciones_cxp->uf_obtenervalor("txtfecfac".$li_fila,""));
			$ls_numfac=trim($io_funciones_cxp->uf_obtenervalor("txtnumfac".$li_fila,""));
			$ls_numcon=trim($io_funciones_cxp->uf_obtenervalor("txtnumcon".$li_fila,""));
			$ls_numnd=trim($io_funciones_cxp->uf_obtenervalor("txtnumnd".$li_fila,""));
			$ls_numnc=trim($io_funciones_cxp->uf_obtenervalor("txtnumnc".$li_fila,""));
			$ls_tiptrans=trim($io_funciones_cxp->uf_obtenervalor("txttiptrans".$li_fila,""));
			$ls_totcmp_sin_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotsiniva".$li_fila,"0,00"));
			$ls_totcmp_con_iva=trim($io_funciones_cxp->uf_obtenervalor("txttotconiva".$li_fila,"0,00"));
			$ls_basimp=trim($io_funciones_cxp->uf_obtenervalor("txtbasimp".$li_fila,"0,00"));
			$ls_porimp=trim($io_funciones_cxp->uf_obtenervalor("txtporimp".$li_fila,"0,00"));
			$ls_totimp=trim($io_funciones_cxp->uf_obtenervalor("txttotimp".$li_fila,"0,00"));
			$ls_ivaret=trim($io_funciones_cxp->uf_obtenervalor("txtivaret".$li_fila,"0,00"));
			$ls_porret=trim($io_funciones_cxp->uf_obtenervalor("txtporret".$li_fila,"0,00"));
			$ls_numsop=trim($io_funciones_cxp->uf_obtenervalor("txtnumsop".$li_fila,""));
			$ls_numdoc=trim($io_funciones_cxp->uf_obtenervalor("txtnumdoc".$li_fila,""));

			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numope."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numnd."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numnc."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_fecfac."' readonly>";
			$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' readonly><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
					 			 	 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."' readonly>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' readonly size=15>";
			$lo_object[$li_fila][15]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."   class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."   class=sin-borde  style=text-align:center size=10 value='' readonly>";
		$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly value=''>";
		$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>";
		$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly value=''>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
		$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly >"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly>";
		$lo_object[$li_fila][15]="<a><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_dt_cmpret($as_numcom,$as_codret)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud
		//                 ai_total   // Total de la Solicitud
		//	  Description: Método que busca las recepciones de documento asociadas y las imprime
		//	   Creado Por: 
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp;

		// Titulos del Grid
		$lo_title[1]=utf8_encode("Nro. Operacion");
		$lo_title[2]="Factura";
		$lo_title[3]="Nro. Control";
		$lo_title[4]="Nota Debito"; 
		$lo_title[5]="Nota Credito"; 
		$lo_title[6]="Fecha"; 
		$lo_title[7]="Total con IVA"; 
		$lo_title[8]="Total sin IVA"; 
		$lo_title[9]="Base Imponible"; 
		$lo_title[10]="Porcentaje Impuesto"; 
		$lo_title[11]="Total Impuesto";
		$lo_title[12]="Iva Retenido"; 
		$lo_title[13]="Nro. Documento";  
		$lo_title[14]="Nro. Solicitud"; 
		$lo_title[15]="Editar";
		
		$lo_object[0]="";
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("sigesp_cxp_c_modcmpret.php");
		$io_modcmpret=new sigesp_cxp_c_modcmpret("../../");
		$rs_data = $io_modcmpret->uf_load_dt_cmpret($as_numcom,$as_codret);
		$li_fila=0;
		
		while($row=$io_modcmpret->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_numope=trim($row["numope"]);
			$ls_numfac=trim($row["numfac"]);
			$ls_numcon=trim($row["numcon"]);
			$ls_fecfac=$io_funciones->uf_convertirfecmostrar($row["fecfac"]);
			$ls_totcmp_sin_iva=number_format($row["totcmp_sin_iva"],2,",",".");
			$ls_totcmp_con_iva=number_format($row["totcmp_con_iva"],2,",",".");
			$ls_basimp=number_format($row["basimp"],2,",",".");
			$ls_porimp=number_format($row["porimp"],2,",",".");
			$ls_totimp=number_format($row["totimp"],2,",",".");
			$ls_ivaret=number_format($row["iva_ret"],2,",",".");
			$ls_porret=number_format($row["iva_ret"],2,",",".");
			$ls_numdoc=trim($row["numdoc"]);
			$ls_codret=trim($row["codret"]);
			$ls_numsop=trim($row["numsop"]);
			$ls_numnd=trim($row["numnd"]);
			$ls_numnc=trim($row["numnc"]);
			$ls_tiptrans=trim($row["tiptrans"]);
			
			
			$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."  class=sin-borde  style=text-align:center size=10 value='".$ls_numope."' readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila." value='".$ls_codret."'>";
			$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."  class=sin-borde  style=text-align:center size=10 value='".$ls_numfac."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."  class=sin-borde  style=text-align:right size=10 value='".$ls_numcon."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numnd."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."   class=sin-borde  style=text-align:center size=10 value='".$ls_numnc."' readonly>";
			$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."  class=sin-borde  style=text-align:center size=10 value='".$ls_fecfac."' readonly>";
			$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_con_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totcmp_sin_iva."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_basimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 value='".$ls_porimp."' readonly; ><a href=javascript:uf_iva(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Otros Créditos !!!' width=15 height=15 border=0></a>";
			$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_totimp."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); >";
			$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_ivaret."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); ><a href=javascript:uf_retenciones(".$li_fila.");><img src=../shared/imagebank/tools15/buscar.gif alt='Buscar Retenciones !!!' width=15 height=15 border=0></a>".
					 			     "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_porret."' readonly>";
			$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 value='".$ls_numdoc."' readonly>"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." value='".$ls_tiptrans."'>";
			$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde value='".$ls_numsop."' size=13 readonly size=15>";
			$lo_object[$li_fila][15]="<a href=javascript:ue_delete_detalle('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		}
		$li_fila=$li_fila+1;
		$lo_object[$li_fila][1]="<input name=txtnumope".$li_fila." type=text id=txtnumope".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>"."<input name=txtcodret".$li_fila." type=hidden id=txtcodret".$li_fila.">"."<input name=txtnumsop".$li_fila." type=hidden id=txtnumsop".$li_fila." >";
		$lo_object[$li_fila][2]="<input name=txtnumfac".$li_fila." type=text id=txtnumfac".$li_fila."   class=sin-borde  style=text-align:center size=10 readonly>";
		$lo_object[$li_fila][3]="<input name=txtnumcon".$li_fila." type=text id=txtnumcon".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][4]="<input name=txtnumnd".$li_fila."  type=text id=txtnumnd".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>";
		$lo_object[$li_fila][5]="<input name=txtnumnc".$li_fila."  type=text id=txtnumnc".$li_fila."   class=sin-borde  style=text-align:center size=10  readonly>";
		$lo_object[$li_fila][6]="<input name=txtfecfac".$li_fila." type=text id=txtfecfac".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly>";
		$lo_object[$li_fila][7]="<input name=txttotconiva".$li_fila." type=text id=txttotconiva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][8]="<input name=txttotsiniva".$li_fila." type=text id=txttotsiniva".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][9]="<input name=txtbasimp".$li_fila." type=text id=txtbasimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][10]="<input name=txtporimp".$li_fila." type=text id=txtporimp".$li_fila."   class=sin-borde  style=text-align:right size=10 readonly value=''>";
		$lo_object[$li_fila][11]="<input name=txttotimp".$li_fila." type=text id=txttotimp".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][12]="<input name=txtivaret".$li_fila." type=text id=txtivaret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>".
					 			 "<input name=txtporret".$li_fila." type=hidden id=txtporret".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>";
		$lo_object[$li_fila][13]="<input name=txtnumdoc".$li_fila." type=text id=txtnumdoc".$li_fila."   class=sin-borde  style=text-align:right size=12 readonly>"."<input name=txtnumnd".$li_fila." type=hidden id=txtnumnd".$li_fila." >"."<input name=txtnumnc".$li_fila." type=hidden id=txtnumnc".$li_fila." >"."<input name=txttiptrans".$li_fila." type=hidden id=txttiptrans".$li_fila." >";
		$lo_object[$li_fila][14]="<input name=txtnumsop".$li_fila." type=text id=txtnumsop".$li_fila." class=sin-borde readonly size=15>";
		$lo_object[$li_fila][15]="<a><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		unset($io_modcmpret);		
		
		
		print "    <tr>";
		print " 	  <td height='22' align='left'><a href='javascript:ue_insert_row();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle' width='20' height='20' border='0'>Agregar Detalle</a></td>";
		print "    </tr>";
		print "<br>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,720,"Detalle Comprobante","gridrecepciones");
		
	}// end function uf_load_dt_cmpret
	//-----------------------------------------------------------------------------------------------------------------------------------
?>