<?php
	//-----------------------------------------------------------------------------------------------------------------------------------
	// Clase donde se cargan todos los catálogos del sistema SEP con la utilización del AJAX
	//-----------------------------------------------------------------------------------------------------------------------------------
    session_start();   
	require_once("class_funciones_scf.php");
	$io_funciones_scf=new class_funciones_scf("../../");
	// Tipo del catalogo que se requiere pintar
	$ls_catalogo=$io_funciones_scf->uf_obtenervalor("catalogo",""); 
	switch($ls_catalogo)
	{
		case "PROVEEDOR":
			uf_print_proveedor();
			break;
		case "BENEFICIARIO":
			uf_print_beneficiario();
			break;
		case "CUENTASSCG":
			uf_print_cuentasscg();
			break;
		case "COMPROBANTE":
			uf_print_comprobante();
			break;
		case "PROCEDE":
			uf_print_procede();
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedor()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_codpro="%".$_POST['codpro']."%";
		$ls_nompro="%".$_POST['nompro']."%";
		$ls_dirpro="%".$_POST['dirpro']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
        $ls_sql="SELECT cod_pro,nompro,sc_cuenta,rifpro".
				"  FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$ls_codpro."' ".
				"   AND nompro like '".$ls_nompro."' ".
				"   AND dirpro like '".$ls_dirpro."' ". 
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Proveedores","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Codigo' align='center' onClick=ue_orden('cod_pro')>Codigo</td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nompro')>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=utf8_encode($row["nompro"]);
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_rifpro=$row["rifpro"];
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
					
					case "catcomp":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptarcatcomp('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td>".$ls_nompro."</td>";
						print "</tr>";
					break;
				}
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_beneficiario()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_beneficiario
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_cedbene="%".$_POST['cedbene']."%";
		$ls_nombene="%".$_POST['nombene']."%";
		$ls_apebene="%".$_POST['apebene']."%";
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT ced_bene, nombene, apebene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND ced_bene <> '----------' ".
				"   AND ced_bene like '".$ls_cedbene."' ".
				"   AND nombene like '".$ls_nombene."' ".
				"   AND apebene like '".$ls_apebene."' ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Beneficiarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td style='cursor:pointer' title='Ordenar por Cedula' align='center' onClick=ue_orden('ced_bene')>Cedula </td>";
			print "<td style='cursor:pointer' title='Ordenar por Nombre' align='center' onClick=ue_orden('nombene')>Nombre</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["ced_bene"];
				$ls_nombene=utf8_encode($row["nombene"]." ".$row["apebene"]);
				switch ($ls_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;

					case "catcomp":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarcatcomp('$ls_cedbene');\">".$ls_cedbene."</a></td>";
						print "<td>".$ls_nombene."</td>";
						print "</tr>";
					break;
				}					
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentasscg()
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentasscg
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que inprime el resultado de la busqueda de las cuentas contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		$ls_scgcuenta="%".$_POST['scgcuenta']."%";
		$ls_dencue="%".$_POST['dencue']."%";
        $ls_codemp=$_SESSION['la_empresa']['codemp'];
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT sc_cuenta, denominacion, status ".
			    "  FROM scg_cuentas ".
				" WHERE codemp = '".$ls_codemp."'  ".
				"	AND sc_cuenta like '".$ls_scgcuenta."' ".
				"   AND denominacion like '".$ls_dencue."' ".								
				" ORDER BY ".$ls_campoorden." ".$ls_orden." ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Cuentas Contables ","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Cuenta Contable' align='center' onClick=ue_orden('sc_cuenta')>Cuenta Contable</td>";
			print "<td width=400 style='cursor:pointer' title='Ordenar por Denominacion'    align='center' onClick=ue_orden('denominacion')>Denominacion</td>";
			print "</tr>";
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta=trim($row["sc_cuenta"]);
				$ls_status=trim($row["status"]);
				$ls_denominacion=utf8_encode(rtrim($row["denominacion"]));
				switch($ls_tipo)
				{
					case "":
						if($ls_status=="C")
						{
							print "<tr class=celdas-azules>";
							print "<td align='center'><a href=\"javascript: ue_aceptar('".$ls_sccuenta."','".$ls_denominacion."');\">".$ls_sccuenta."</a></td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						else
						{
							print "<tr class=celdas-blancas>";
							print "<td align='center'>".$ls_sccuenta."</td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						break;
					
					case "REPDES":
						if($ls_status=="C")
						{
							print "<tr class=celdas-azules>";
							print "<td align='center'><a href=\"javascript: ue_aceptarrepdes('".$ls_sccuenta."');\">".$ls_sccuenta."</a></td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						else
						{
							print "<tr class=celdas-blancas>";
							print "<td align='center'>".$ls_sccuenta."</td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						break;
					
					case "REPHAS":
						if($ls_status=="C")
						{
							print "<tr class=celdas-azules>";
							print "<td align='center'><a href=\"javascript: ue_aceptarrephas('".$ls_sccuenta."');\">".$ls_sccuenta."</a></td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						else
						{
							print "<tr class=celdas-blancas>";
							print "<td align='center'>".$ls_sccuenta."</td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						}
						break;
						
					case "REPDESTOD":
							print "<tr class=celdas-blancas>";
							print "<td align='center'><a href=\"javascript: ue_aceptarrepdes('".$ls_sccuenta."');\">".$ls_sccuenta."</a></td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						break;
					
					case "REPHASTOD":
							print "<tr class=celdas-blancas>";
							print "<td align='center'><a href=\"javascript: ue_aceptarrephas('".$ls_sccuenta."');\">".$ls_sccuenta."</a></td>";
							print "<td align='left'>".$ls_denominacion."</td>";
							print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_cuentasscg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_comprobante()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_comprobante
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de Comprobantes Contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/06/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		$ls_comprobante="%".$_POST['comprobante']."%";
		$ls_procede="%".$_POST['procede']."%";
		$ls_tipdes="%".$_POST['tipdes']."%";
		$ls_codigo="%".$_POST['codigo']."%";
		$ls_codpro="";
		$ls_cedbene="";
		switch($ls_tipdes)
		{
			case "P":
				$ls_codpro=$ls_codigo;
				$ls_cedbene="----------";
				break;
			case "B":
				$ls_codpro="----------";
				$ls_cedbene=$ls_codigo;
				break;
		}
		$ls_fecdes=$io_funciones->uf_convertirdatetobd($_POST['fecdes']);
		$ls_fechas=$io_funciones->uf_convertirdatetobd($_POST['fechas']);;
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT sigesp_cmp.procede, sigesp_cmp.comprobante, sigesp_cmp.fecha, sigesp_cmp.descripcion, sigesp_cmp.tipo_destino, ".
				"		sigesp_cmp.cod_pro, sigesp_cmp.ced_bene, sigesp_cmp.codban, sigesp_cmp.ctaban, rpc_beneficiario.nombene, ".
				"		rpc_beneficiario.apebene, rpc_proveedor.nompro ".
				"  FROM sigesp_cmp, rpc_beneficiario, rpc_proveedor ".
				" WHERE sigesp_cmp.codemp='".$ls_codemp."' ".
				"   AND sigesp_cmp.tipo_comp=1 ".
				"   AND sigesp_cmp.comprobante like '".$ls_comprobante."' ".
				"   AND sigesp_cmp.procede like '".$ls_procede."' ".
				"   AND sigesp_cmp.tipo_destino like '".$ls_tipdes."' ".
				"   AND sigesp_cmp.cod_pro like '%".$ls_codpro."%' ".
				"   AND sigesp_cmp.ced_bene like '%".$ls_cedbene."%' ".
				"   AND sigesp_cmp.fecha>='".$ls_fecdes."' ".
				"   AND sigesp_cmp.fecha<='".$ls_fechas."' ".
				"   AND sigesp_cmp.codemp IN (SELECT scg_dt_cmp.codemp FROM scg_dt_cmp ".
				"							   WHERE scg_dt_cmp.codemp = sigesp_cmp.codemp ".
				"							     AND scg_dt_cmp.procede = sigesp_cmp.procede ".
				"							     AND scg_dt_cmp.comprobante = sigesp_cmp.comprobante ".
				"							     AND scg_dt_cmp.fecha = sigesp_cmp.fecha ".
				"							     AND scg_dt_cmp.codban = sigesp_cmp.codban ".
				"							     AND scg_dt_cmp.ctaban = sigesp_cmp.ctaban) ".
				"	AND sigesp_cmp.codemp = rpc_beneficiario.codemp ".
				"	AND sigesp_cmp.ced_bene = rpc_beneficiario.ced_bene ".
				"	AND sigesp_cmp.codemp = rpc_proveedor.codemp ".
				"	AND sigesp_cmp.cod_pro = rpc_proveedor.cod_pro ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Beneficiarios","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=60 style='cursor:pointer' title='Ordenar por Comprobante'  align='center' onClick=ue_orden('sigesp_cmp.comprobante')>Comprobante</td>";
			print "<td width=60 style='cursor:pointer' title='Ordenar por Procede'      align='center' onClick=ue_orden('sigesp_cmp.procede')>Procede</td>";
			print "<td width=60 style='cursor:pointer' title='Ordenar por Fecha'        align='center' onClick=ue_orden('sigesp_cmp.fecha')>Fecha</td>";
			print "<td width=60 style='cursor:pointer' title='Ordenar por Proveedor'    align='center' onClick=ue_orden('sigesp_cmp.cod_pro')>Proveedor</td>";
			print "<td width=60 style='cursor:pointer' title='Ordenar por Beneficiario' align='center' onClick=ue_orden('sigesp_cmp.ced_bene')>Beneficiario</td>";
			print "<td width=300>Descripción</td>";
			print "</tr>";
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_comprobante=$row["comprobante"];
				$ls_procede=$row["procede"];
				$ls_tipdes=$row["tipo_destino"];
				switch($ls_tipdes)
				{
					case "P":
						$ls_codigo=$row["cod_pro"];
						$ls_nombre=$row["nompro"];
						break;
					case "B":
						$ls_codigo=$row["ced_bene"];
						$ls_nombre=$row["apebene"].", ".$row["nombene"];
						break;
					default:
						$ls_codigo="";
						$ls_nombre="";
				}
				$ls_descripcion=$row["descripcion"];
				$ld_fecha=$io_funciones->uf_convertirfecmostrar($row["fecha"]);
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				$ls_codban=$row["codban"];
				$ls_ctaban=$row["ctaban"];
				switch ($ls_tipo)
				{
					case "":
						
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar('$ls_comprobante','$ls_procede','$ld_fecha','$ls_tipdes',".
							  "'$ls_codigo','$ls_nombre','$ls_codban','$ls_ctaban','$li_i','$ls_codpro','$ls_cedbene');\">".$ls_comprobante."</a></td>";
						print "<td>".$ls_procede."</td>";
						print "<td>".$ld_fecha."</td>";
						print "<td>".$ls_codpro."</td>";
						print "<td>".$ls_cedbene."</td>";
						print "<td><input name='txtdescripcion".$li_i."' type='hidden' id='txtdescripcion".$li_i."' value='".$ls_descripcion."'> ".$ls_descripcion."</td>";
						print "</tr>";
					break;
					
					case "REPDES":
						
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarrepdes('$ls_comprobante');\">".$ls_comprobante."</a></td>";
						print "<td>".$ls_procede."</td>";
						print "<td>".$ld_fecha."</td>";
						print "<td>".$ls_codpro."</td>";
						print "<td>".$ls_cedbene."</td>";
						print "<td><input name='txtdescripcion".$li_i."' type='hidden' id='txtdescripcion".$li_i."' value='".$ls_descripcion."'> ".$ls_descripcion."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarrephas('$ls_comprobante');\">".$ls_comprobante."</a></td>";
						print "<td>".$ls_procede."</td>";
						print "<td>".$ld_fecha."</td>";
						print "<td>".$ls_codpro."</td>";
						print "<td>".$ls_cedbene."</td>";
						print "<td><input name='txtdescripcion".$li_i."' type='hidden' id='txtdescripcion".$li_i."' value='".$ls_descripcion."'> ".$ls_descripcion."</td>";
						print "</tr>";
					break;
				}					
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_procede()
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_procede
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de Procedes
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/08/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		$ls_orden=$_POST['orden'];
		$ls_campoorden=$_POST['campoorden'];
		$ls_tipo=$_POST['tipo'];
		$ls_sql="SELECT procede, desproc ".
				"  FROM sigesp_procedencias ".
				" ORDER BY ".$ls_campoorden." ".$ls_orden."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar Procedencias","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td width=100 style='cursor:pointer' title='Ordenar por Comprobante' align='center' onClick=ue_orden('procede')>Procedencia</td>";
			print "<td width=400 style='cursor:pointer' title='Ordenar por Descripción' align='left'   onClick=ue_orden('desproc')>Descripción</td>";
			print "</tr>";
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_procede=$row["procede"];
				$ls_desproc=$row["desproc"];
				switch ($ls_tipo)
				{
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptarrepdes('$ls_procede');\">".$ls_procede."</a></td>";
						print "<td align='left'>".$ls_desproc."</td>";
						print "</tr>";
					break;
					
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td align='center'><a href=\"javascript: ue_aceptarrephas('$ls_procede');\">".$ls_procede."</a></td>";
						print "<td align='left'>".$ls_desproc."</td>";
						print "</tr>";
					break;
				}					
			}
			$io_sql->free_result($rs_data);
			print "</table>";
		}
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}// end function uf_print_procede
	//-----------------------------------------------------------------------------------------------------------------------------------
?>