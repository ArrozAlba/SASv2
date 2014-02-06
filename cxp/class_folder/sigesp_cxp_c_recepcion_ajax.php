<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	require_once("../../shared/class_folder/class_datastore.php");
	$io_ds_spgcuentas=new class_datastore(); // Datastored de cuentas contables
	$io_ds_scgcuentas=new class_datastore(); // Datastored de cuentas contables
	$io_ds_cargos=new class_datastore(); // Datastored de cargos
	$io_ds_deducciones=new class_datastore(); // Datastored de Deducciones
	$io_ds_aux=new class_datastore(); // Datastored de Axuliar
	$io_ds_amortizaciones=new class_datastore(); // Datastored de Axuliar
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	if(!(array_key_exists("ls_ajuste",$_SESSION)))
	{
		$_SESSION["ls_ajuste"]="";
	}
	// proceso a ejecutar
	$ls_proceso=$io_funciones_cxp->uf_obtenervalor("proceso","");
	// Número de recepción de documentos
	$ls_numrecdoc=trim($io_funciones_cxp->uf_obtenervalor("numrecdoc",""));
	// Tipo de Documento
	$ls_codtipdoc=trim($io_funciones_cxp->uf_obtenervalor("codtipdoc",""));
	// Cédula del beneficiario
	$ls_cedbene=trim($io_funciones_cxp->uf_obtenervalor("cedbene",""));
	// Código del proveedor
	$ls_codpro=trim($io_funciones_cxp->uf_obtenervalor("codpro",""));
	// Número de Compromiso del causado parcial
	$ls_compromiso=$io_funciones_cxp->uf_obtenervalor("compromiso","");
	// Procede del Compromiso del causado parcial
	$ls_procededoc=$io_funciones_cxp->uf_obtenervalor("procededoc","");
	// Monto del Compromiso del causado parcial
	$li_montobaseimp=$io_funciones_cxp->uf_obtenervalor("montobaseimp",0);
	// total de filas de cuentas presupuestarias
	$li_totrowspg=$io_funciones_cxp->uf_obtenervalor("totrowspg",1);
	// total de filas de cuentas contables
	$li_totrowscg=$io_funciones_cxp->uf_obtenervalor("totrowscg",1);
	// total de filas de cuentas de cargos
	$li_totrowcargos=$io_funciones_cxp->uf_obtenervalor("totrowcargos",0);
	// Total de filas de las deducciones
	$li_totrowdeducciones=$io_funciones_cxp->uf_obtenervalor("totrowdeducciones","0");
	// estatus contable
	$ls_estcontable=$io_funciones_cxp->uf_obtenervalor("estcontable","");
	// estatus presupuestario
	$ls_estpresupuestario=$io_funciones_cxp->uf_obtenervalor("estpresupuestario","");
	// sub total 
	$li_subtotal=$io_funciones_cxp->uf_obtenervalor("subtotal","0,00");
	// total de cargos
	$li_cargos=$io_funciones_cxp->uf_obtenervalor("cargos","0,00");
	// total de deducciones
	$li_deducciones=$io_funciones_cxp->uf_obtenervalor("deducciones","0,00");
	// total 
	$li_total=$io_funciones_cxp->uf_obtenervalor("total","0,00");
	// total general
	$li_totgeneral=$io_funciones_cxp->uf_obtenervalor("totgeneral","0,00");
	// Si se deben cargar las cuentas de cargos 
	$li_cargarcargos=$io_funciones_cxp->uf_obtenervalor("cargarcargos","0");
	// Si se deben eliminar los cargos de un compromiso
	$li_eliminarcargo=$io_funciones_cxp->uf_obtenervalor("eliminarcargo","0");
	// Si se deben cargar las cuentas de Deducciones
	$li_cargardeducciones=$io_funciones_cxp->uf_obtenervalor("cargardeducciones","0");
	// Si se deben cargar los comprobantes de su origen
	$li_cargarcomprobantes=$io_funciones_cxp->uf_obtenervalor("cargarcomprobantes","0");
	// Si se deben generar las cuentas contables automáticamente ó no
	$li_generarcontable=$io_funciones_cxp->uf_obtenervalor("generarcontable","0");
	// Si se esta cerrando un asiento
	$li_cerrarasiento=$io_funciones_cxp->uf_obtenervalor("cerrarasiento","0");
	// Si se hizo algún ajuste en los cargos
	$ls_ajuste=$io_funciones_cxp->uf_obtenervalor("ajuste","");
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	$ls_estempcon=$_SESSION["la_empresa"]["estempcon"];
	$ls_basdatcon=$_SESSION["la_empresa"]["basdatcon"];
	if(!empty($ls_ajuste))
	{
		$_SESSION["ls_ajuste"]=$_SESSION["ls_ajuste"].$ls_ajuste;
	}
	// Indica si el IVA es contable o presupuestario  P=>Presupuestario C=>Contable
	$ls_confiva=$_SESSION["la_empresa"]["confiva"];
	
	switch($ls_proceso)
	{
		case "COMPROMETECAUSA":
			if($ls_estpresupuestario=="2")
			{// Compromete y Causa ó Causa pinto las cuentas presupuestarias
				uf_print_grid_presupuesto($li_totrowspg,$ls_estcontable,$ls_estpresupuestario,$li_generarcontable);
			}
			if($li_cargarcargos=="1")
			{	// Cargamos los cargos de ser necesarios
				uf_print_cuentas_presupuesto_cargos($ls_estcontable,$li_generarcontable,$li_totrowcargos);
			}
			if($ls_estcontable=="1")
			{// Cargamos las cuentas contables que son manuales
				 uf_print_grid_contable($li_totrowscg);
			}
			uf_print_cuentas_deducciones($li_totrowdeducciones,$li_cargardeducciones,$ls_estcontable,$li_generarcontable);
			uf_print_cuentas_presupuesto($ls_estpresupuestario);
			if($ls_estcontable=="1")
			{// si hay afectación contable
				uf_print_cuentas_contable($ls_estcontable,$li_generarcontable);
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total,$li_deducciones,$li_totgeneral);
			break;

		case "CAUSA":
		
			if($ls_estpresupuestario=="1")
			{// Causa pinto las cuentas presupuestarias
				uf_print_grid_presupuesto($li_totrowspg,$ls_estcontable,$ls_estpresupuestario,$li_generarcontable);
			}
			if(($ls_estpresupuestario=="1")&&($li_cargarcomprobantes=="1"))
			{// Causa cargo los detalles de los comprobantes
				uf_print_comprobantes_presupuesto($ls_estcontable,$li_generarcontable);
			}
			if($li_eliminarcargo=="1")
			{// elimino los cargos de un comprobante
				uf_delete_cargos();
			}
			if($li_cargarcargos=="1")
			{	// Cargamos los cargos de un compromiso en particular
				uf_print_ajustar_cuentas_presupuesto_cargos($ls_estcontable,$li_generarcontable,$li_totrowcargos);
			}
			if($ls_estcontable=="1")
			{// Cargamos las cuentas contables que son manuales
				 uf_print_grid_contable($li_totrowscg);
			}
			uf_print_cuentas_deducciones($li_totrowdeducciones,$li_cargardeducciones,$ls_estcontable,$li_generarcontable);
			uf_print_cuentas_presupuesto($ls_estpresupuestario);
			if($ls_estcontable=="1")
			{// si hay afectación contable
				uf_print_cuentas_contable($ls_estcontable,$li_generarcontable);
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total,$li_deducciones,$li_totgeneral);
			break;

		case "CAUSAPARCIAL":
			if($ls_estpresupuestario=="1")
			{// Causa pinto las cuentas presupuestarias
				uf_print_grid_presupuesto($li_totrowspg,$ls_estcontable,$ls_estpresupuestario,$li_generarcontable);
			}
			if($li_eliminarcargo=="1")
			{// elimno los cargos de un comprobante
				uf_delete_cargos();
			}
			if($li_cargarcargos=="1")
			{	// Cargamos los cargos de un compromiso en particular
				uf_print_ajustar_cuentas_presupuesto_cargos($ls_estcontable,$li_generarcontable,$li_totrowcargos);
			}
			if($ls_estcontable=="1")
			{// Cargamos las cuentas contables que son manuales
				 uf_print_grid_contable($li_totrowscg);
			}
			uf_print_cuentas_deducciones($li_totrowdeducciones,$li_cargardeducciones,$ls_estcontable,$li_generarcontable);
			uf_print_cuentas_presupuesto($ls_estpresupuestario);
			if($ls_estcontable=="1")
			{// si hay afectación contable
				uf_print_cuentas_contable($ls_estcontable,$li_generarcontable);
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total,$li_deducciones,$li_totgeneral);
			break;

		case "CONTABLE":
			uf_print_cuentas_presupuesto($ls_estpresupuestario);
			if($ls_estcontable=="1")
			{// Cargamos las cuentas contables que son manuales
				 uf_print_grid_contable($li_totrowscg);
			}
			uf_print_cuentas_deducciones($li_totrowdeducciones,$li_cargardeducciones,$ls_estcontable,$li_generarcontable);
			if($ls_estcontable=="1")
			{// si hay afectación contable
				uf_print_cuentas_contable($ls_estcontable,$li_generarcontable);
			}
			uf_print_total($li_subtotal,$li_cargos,$li_total,$li_deducciones,$li_totgeneral);
			break;

		case "LOADRECEPCION":
			unset($_SESSION["ls_ajuste"]);
			unset($_SESSION["cargos"]);
			unset($_SESSION["amortizacion"]);
			unset($_SESSION["deducciones"]);
			uf_load_cuentas_presupuesto($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro,$ls_estcontable,$ls_estpresupuestario);
			uf_load_cuentas_contables($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro,$ls_estcontable,$ls_estpresupuestario,$li_generarcontable);
			uf_load_cargos($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro,$ls_estcontable,$ls_estpresupuestario);
			uf_load_deducciones($ls_numrecdoc,$ls_codtipdoc,$ls_cedbene,$ls_codpro,$ls_estcontable,$ls_estpresupuestario);
			uf_print_total($li_subtotal,$li_cargos,$li_total,$li_deducciones,$li_totgeneral);
			break;
			
		case "VERIFICAR_RD":
			require_once("sigesp_cxp_c_recepcion.php");
			$io_recepcion=new sigesp_cxp_c_recepcion("../../");
			$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
			$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
			$ls_tipdes=$io_funciones_cxp->uf_obtenervalor("tipdes","");
			$ls_codigo=$io_funciones_cxp->uf_obtenervalor("codigo","");
			$lb_valido=$io_recepcion->uf_select_recepcion($ls_numrecdoc,$ls_tipdes,$ls_codigo,$ls_codtipdoc);
			if($lb_valido)
			{
				print "ERROR->La Recepción de Documentos para este Proveedor/Beneficiario y Tipo de Documento ya existe.";
			}
			unset($io_recepcion);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_grid_presupuesto($ai_totrowspg,$as_estcontable,$as_estpresupuestario,$ai_generarcontable)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_grid_presupuesto
		//		   Access: private
		//	    Arguments: ai_totrowspg    // Total de filas de presupuesto
		//				   as_estcontable  // estatus contable
		//				   as_estpresupuestario // estatus presupuestario
		//				   ai_generarcontable  // Generar asiento contable automático 
		//				   lo_object  //  arreglo de objetos que van a conformar las cuentas de presupuesto
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_scgcuentas, $io_ds_spgcuentas,$ls_estempcon,$ls_basdatcon ;
		// Recorrido del Grid de Cuentas Presupuestarias
		for($li_fila=1;$li_fila<$ai_totrowspg;$li_fila++)
		{
			$ls_nrocomp=trim($io_funciones_cxp->uf_obtenervalor("txtspgnrocomp".$li_fila,""));
			$ls_codpro=trim($io_funciones_cxp->uf_obtenervalor("txtcodpro".$li_fila,""));
			$ls_estcla=trim($io_funciones_cxp->uf_obtenervalor("txtestcla".$li_fila,""));
			$ls_cuenta=trim($io_funciones_cxp->uf_obtenervalor("txtspgcuenta".$li_fila,""));
			$ls_sccuenta=trim($io_funciones_cxp->uf_obtenervalor("txtspgsccuenta".$li_fila,""));
			$ls_cargo=trim($io_funciones_cxp->uf_obtenervalor("txtcargo".$li_fila,""));
			$li_moncue=trim($io_funciones_cxp->uf_obtenervalor("txtspgmonto".$li_fila,"0,00"));
			$li_original=trim($io_funciones_cxp->uf_obtenervalor("txtoriginal".$li_fila,0));
			$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("txtspgprocededoc".$li_fila,"CXPRCD"));
			$ls_codfuefin=trim($io_funciones_cxp->uf_obtenervalor("txtcodfuefin".$li_fila,"--"));
			$ls_tipbieordcom=trim($io_funciones_cxp->uf_obtenervalor("txttipbieordcom".$li_fila,"-"));
			$ls_estint=trim($io_funciones_cxp->uf_obtenervalor("txtestint".$li_fila,"-"));
			$ls_cuentaint=trim($io_funciones_cxp->uf_obtenervalor("txtcuentaint".$li_fila,"-"));
			$li_monto=str_replace(".","",$li_moncue);
			$li_monto=str_replace(",",".",$li_monto);	
			// Llenamos el datastored de las cuentas presupuestarias
			$io_ds_spgcuentas->insertRow("spgnrocomp",$ls_nrocomp);			
			$io_ds_spgcuentas->insertRow("spgcuenta",$ls_cuenta);			
			$io_ds_spgcuentas->insertRow("spgmonto",$li_monto);			
			$io_ds_spgcuentas->insertRow("codpro",$ls_codpro);			
			$io_ds_spgcuentas->insertRow("estcla",$ls_estcla);			
			$io_ds_spgcuentas->insertRow("cargo",$ls_cargo);			
			$io_ds_spgcuentas->insertRow("original",$li_original);			
			$io_ds_spgcuentas->insertRow("spgsccuenta",$ls_sccuenta);			
			$io_ds_spgcuentas->insertRow("spgprocededoc",$ls_procede);			
			$io_ds_spgcuentas->insertRow("codfuefin",$ls_codfuefin);			
			$io_ds_spgcuentas->insertRow("tipbieordcom",$ls_tipbieordcom);
			$io_ds_spgcuentas->insertRow("estint",$ls_estint);
			$io_ds_spgcuentas->insertRow("cuentaint",$ls_cuentaint);

			if(($ai_generarcontable=="1")&&($as_estcontable=="1"))
			{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
				$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);
				if(($ls_tipbieordcom=="A")&&($ls_estint==1)&&($ls_estempcon!=1)&&($ls_basdatcon!=""))
				{
					$io_ds_scgcuentas->insertRow("scgcuenta",$ls_cuentaint);
				}
				else
				{
					$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);
				}
				$io_ds_scgcuentas->insertRow("debhab","D");			
				$io_ds_scgcuentas->insertRow("estatus","A");			
				$io_ds_scgcuentas->insertRow("mondeb",$li_monto);			
				$io_ds_scgcuentas->insertRow("monhab","0");			
				$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
			}
		}
	}// end function uf_print_grid_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_grid_contable($ai_totrowscg)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_grid_contable
		//		   Access: private
		//	    Arguments: ai_totrowscg    // Total de filas de contabilidad
		//	  Description: Método que imprime el grid de las cuentas contables
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 16/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_scgcuentas,$io_ds_amortizaciones;
		// Recorrido del Grid de Cuentas Contables
		for($li_fila=1;$li_fila<$ai_totrowscg;$li_fila++)
		{
			$ls_nrocomp=trim($io_funciones_cxp->uf_obtenervalor("txtscgnrocomp".$li_fila,""));
			$ls_cuenta=trim($io_funciones_cxp->uf_obtenervalor("txtscgcuenta".$li_fila,""));
			$li_mondeb=trim($io_funciones_cxp->uf_obtenervalor("txtmondeb".$li_fila,""));
			$li_monhab=trim($io_funciones_cxp->uf_obtenervalor("txtmonhab".$li_fila,""));
			$ls_debhab=trim($io_funciones_cxp->uf_obtenervalor("txtdebhab".$li_fila,""));
			$ls_estatus=trim($io_funciones_cxp->uf_obtenervalor("txtestatus".$li_fila,""));
			$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("txtscgprocededoc".$li_fila,"CXPRCD"));
			$li_mondeb=str_replace(".","",$li_mondeb);
			$li_mondeb=str_replace(",",".",$li_mondeb);	
			$li_monhab=str_replace(".","",$li_monhab);
			$li_monhab=str_replace(",",".",$li_monhab);	
			$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);			
			$io_ds_scgcuentas->insertRow("scgcuenta",$ls_cuenta);			
			$io_ds_scgcuentas->insertRow("debhab",$ls_debhab);			
			$io_ds_scgcuentas->insertRow("estatus",$ls_estatus);			
			$io_ds_scgcuentas->insertRow("mondeb",$li_mondeb);			
			$io_ds_scgcuentas->insertRow("monhab",$li_monhab);			
			$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
		}
		$li_totrowamort=trim($io_funciones_cxp->uf_obtenervalor("totrowamort",""));
		if($li_totrowamort>0)
		{
			for($li_fila=1;$li_fila<=$li_totrowamort;$li_fila++)
			{
				$ls_recdocant=trim($io_funciones_cxp->uf_obtenervalor("txtnumrecdoc".$li_fila,""));
				$ls_codtipdoc=trim($io_funciones_cxp->uf_obtenervalor("txtcodtipdoc".$li_fila,""));
				$ls_codamo=trim($io_funciones_cxp->uf_obtenervalor("txtcodamo".$li_fila,""));
				$li_monto=trim($io_funciones_cxp->uf_obtenervalor("txtmonhab".$li_fila,""));
				$li_monto=str_replace(".","",$li_monto);
				$li_monto=str_replace(",",".",$li_monto);	
				$io_ds_amortizaciones->insertRow("recdocant",$ls_recdocant);			
				$io_ds_amortizaciones->insertRow("codtipdoc",$ls_codtipdoc);			
				$io_ds_amortizaciones->insertRow("codamo",$ls_codamo);			
				$io_ds_amortizaciones->insertRow("monto",$li_monto);			
			}
			$_SESSION["amortizacion"]=$io_ds_amortizaciones->data;
		}
	}// end function uf_print_grid_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_comprobantes_presupuesto($as_estcontable,$ai_generarcontable)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_comprobantes_presupuesto
		//		   Access: private
		//	    Arguments: as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//				   ai_totrowcargos // Total de cargos seleccionados
		//				   ai_totrowspg    // Total de filas de presupuesto
		//				   lo_object // arreglo de objetos para las cuentas contables
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto cuando es un causado
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 13/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_ds_scgcuentas, $li_subtotal, $li_cargos, $li_total, $li_deducciones;
		global $io_ds_spgcuentas, $li_totgeneral;
		
		require_once("sigesp_cxp_c_recepcion.php");
		$io_recepcion=new sigesp_cxp_c_recepcion("../../");
		$ls_confiva=$_SESSION["la_empresa"]["confiva"]; 
		$ls_comprobante=trim($io_funciones_cxp->uf_obtenervalor("comprobante",""));
		$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("procede",""));
		$li_total_compromiso=0;
		$li_total_cargos=0;
		switch($ls_procede)
		{
			case "SPGCMP": // Orden de Compra de Bienes
				$lb_valido=$io_recepcion->uf_load_compromiso_spg($ls_comprobante,$ls_procede);
				break;
				
			case "SOCCOC": // Orden de Compra de Bienes
				$lb_valido=$io_recepcion->uf_load_compromiso_soc($ls_comprobante,"B");
				if($lb_valido)
				{
					if($ls_confiva=="P")
					{
						$lb_valido=$io_recepcion->uf_load_cargos_compromiso_soc($ls_comprobante,"B",$li_total_cargos);
					}
					else
					{
						$lb_valido=$io_recepcion->uf_load_cargoscontable_compromiso_soc($ls_comprobante,"B",$li_total_cargos);
					}
				}
				break;
			
			case "SOCCOS": // Orden de Compra de Servicios
				$lb_valido=$io_recepcion->uf_load_compromiso_soc($ls_comprobante,"S");
				if($lb_valido)
				{
					if($ls_confiva=="P")
					{
						$lb_valido=$io_recepcion->uf_load_cargos_compromiso_soc($ls_comprobante,"S",$li_total_cargos);
					}
					else
					{
						$lb_valido=$io_recepcion->uf_load_cargoscontable_compromiso_soc($ls_comprobante,"S",$li_total_cargos);
					}
				}
				break;
			
			case "SNOCNO": // Nómina
					$lb_valido=$io_recepcion->uf_load_compromiso_sno($ls_comprobante);
				break;

			case "SEPSPC": // Solicitud de Ejecución Presupuestaria
				$lb_valido=$io_recepcion->uf_load_compromiso_sep($ls_comprobante);
				if($lb_valido)
				{
					if($ls_confiva=="P")
					{
						$lb_valido=$io_recepcion->uf_load_cargos_compromiso_sep($ls_comprobante,$li_total_cargos);
					}
					else
					{
						$lb_valido=$io_recepcion->uf_load_cargoscontable_compromiso_sep($ls_comprobante,$li_total_cargos);
					}
				}
				break;

			case "SOBCON": // Obras
				$lb_valido=$io_recepcion->uf_load_compromiso_sob($ls_comprobante);
				if($lb_valido)
				{
					if($ls_confiva=="P")
					{
						$lb_valido=$io_recepcion->uf_load_cargos_compromiso_sob($ls_comprobante,$li_total_cargos);
					}
					else
					{
						// para hacer esto hay que hacer en obras que los cargos puedan ser contables
						//$lb_valido=$io_recepcion->uf_load_cargoscontable_compromiso_sob($ls_comprobante,$li_total_cargos);
					}
				}
				break;
		}
		// Recorrido del Grid de Cuentas Presupuestarias que ya estan en el grid
		$li_totrow=$io_recepcion->io_ds_compromisos->getRowCount('comprobante');	
		for($li_fila=1;($li_fila<=$li_totrow)&&($lb_valido);$li_fila++)
		{
			$ls_nrocomp=trim($io_recepcion->io_ds_compromisos->data["comprobante"][$li_fila]);
			$ls_codpro=trim($io_recepcion->io_ds_compromisos->data["codestpro1"][$li_fila]).
					   trim($io_recepcion->io_ds_compromisos->data["codestpro2"][$li_fila]).
					   trim($io_recepcion->io_ds_compromisos->data["codestpro3"][$li_fila]).
					   trim($io_recepcion->io_ds_compromisos->data["codestpro4"][$li_fila]).
					   trim($io_recepcion->io_ds_compromisos->data["codestpro5"][$li_fila]);
			$ls_estcla=trim($io_recepcion->io_ds_compromisos->data["estcla"][$li_fila]);
			$ls_cuenta=trim($io_recepcion->io_ds_compromisos->data["spg_cuenta"][$li_fila]);
			$ls_sccuenta=trim($io_recepcion->io_ds_compromisos->data["sc_cuenta"][$li_fila]);
			$ls_cargo=number_format($io_recepcion->io_ds_compromisos->data["cargo"][$li_fila],0,'','');
			$li_monto=$io_recepcion->io_ds_compromisos->data["monto"][$li_fila];
			$li_original=$io_recepcion->io_ds_compromisos->data["monto"][$li_fila];
			$ls_codfuefin=trim($io_recepcion->io_ds_compromisos->data["codfuefin"][$li_fila]);
			$ls_tipbieordcom=trim($io_recepcion->io_ds_compromisos->data["tipbieordcom"][$li_fila]);
			$ls_estint=trim($io_recepcion->io_ds_compromisos->data["estint"][$li_fila]);
			$ls_cuentaint=trim($io_recepcion->io_ds_compromisos->data["cuentaint"][$li_fila]);
			$ls_programatica="";
			$li_monto_anterior=0;
			$lb_valido=$io_recepcion->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,$ls_estcla,&$li_monto_anterior);
			if($lb_valido)
			{ 
				
				$li_monto=$li_monto-$li_monto_anterior;
				if($ls_cargo=="0")
				{
					$li_total_compromiso=$li_total_compromiso+$li_monto;
				}
				// Llenamos el datastored de las cuentas presupuestarias
				$io_ds_spgcuentas->insertRow("spgnrocomp",$ls_nrocomp);			
				$io_ds_spgcuentas->insertRow("spgcuenta",$ls_cuenta);			
				$io_ds_spgcuentas->insertRow("spgmonto",$li_monto);			
				$io_ds_spgcuentas->insertRow("codpro",$ls_codpro);			
				$io_ds_spgcuentas->insertRow("estcla",$ls_estcla);			
				$io_ds_spgcuentas->insertRow("cargo",$ls_cargo);			
				$io_ds_spgcuentas->insertRow("original",$li_original);			
				$io_ds_spgcuentas->insertRow("spgsccuenta",$ls_sccuenta);			
				$io_ds_spgcuentas->insertRow("spgprocededoc",$ls_procede);
				$io_ds_spgcuentas->insertRow("codfuefin",$ls_codfuefin);			
				$io_ds_spgcuentas->insertRow("tipbieordcom",$ls_tipbieordcom);			
				$io_ds_spgcuentas->insertRow("estint",$ls_estint);			
				$io_ds_spgcuentas->insertRow("cuentaint",$ls_cuentaint);			
				if(($ai_generarcontable=="1")&&($as_estcontable=="1"))
				{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
					$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);			
					if(($ls_tipbieordcom=="A")&&($ls_estint==1)&&($ls_estempcon!=1)&&($ls_basdatcon!=""))
					{
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_cuentaint);
					}
					else
					{
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);
					}	
					$io_ds_scgcuentas->insertRow("debhab","D");			
					$io_ds_scgcuentas->insertRow("estatus","A");			
					$io_ds_scgcuentas->insertRow("mondeb",$li_monto);			
					$io_ds_scgcuentas->insertRow("monhab","0");			
					$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
				}
			}
		}
		$li_subtotal= $li_subtotal+ $li_total_compromiso;
		$li_cargos= $li_cargos + $li_total_cargos;
		$li_total= $li_subtotal + $li_cargos;
		$li_totgeneral=$li_total-$li_deducciones;
	}// end function uf_print_comprobantes_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_presupuesto_cargos($as_estcontable,$ai_generarcontable,$ai_totrowcargos)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_presupuesto_cargos
		//		   Access: private
		//	    Arguments: as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//				   ai_totrowcargos // Total de cargos seleccionados
		//				   ai_totrowspg    // Total de filas de presupuesto
		//				   lo_object // arreglo de objetos para las cuentas contables
		//	  Description: Método que imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_scgcuentas, $io_ds_cargos, $ls_numrecdoc, $io_ds_spgcuentas,$ls_confiva;
		
		// Recorrido del Grid de cargos
		$io_ds_cargos->reset_ds();
		for($li_fila=1;$li_fila<=$ai_totrowcargos;$li_fila++)
		{
			$ls_nrocomp=trim($ls_numrecdoc);
			$ls_codpro=trim($io_funciones_cxp->uf_obtenervalor("codestpro".$li_fila,""));
			$ls_estcla=trim($io_funciones_cxp->uf_obtenervalor("estcla".$li_fila,""));
			$ls_cuenta=trim($io_funciones_cxp->uf_obtenervalor("spgcuenta".$li_fila,""));
			$ls_sccuenta=trim($io_funciones_cxp->uf_obtenervalor("sccuenta".$li_fila,""));
			$ls_formula=trim($io_funciones_cxp->uf_obtenervalor("formula".$li_fila,""));
			$ls_porcar=trim($io_funciones_cxp->uf_obtenervalor("porcar".$li_fila,""));
			$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("procededoc".$li_fila,"CXPRCD"));
			$ls_codfuefin=trim($io_funciones_cxp->uf_obtenervalor("codfuefin".$li_fila,"--"));
			$ls_cargo="1";
			$ls_codcar=trim($io_funciones_cxp->uf_obtenervalor("txtcodcar".$li_fila,""));
			$li_baseimp=trim($io_funciones_cxp->uf_obtenervalor("txtbaseimp".$li_fila,""));
			$li_baseimp=str_replace(".","",$li_baseimp);
			$li_baseimp=str_replace(",",".",$li_baseimp);							
			$li_monimp=trim($io_funciones_cxp->uf_obtenervalor("txtmonimp".$li_fila,"0.00"));
			$li_monimp=str_replace(".","",$li_monimp);
			$li_monimp=str_replace(",",".",$li_monimp);							
			if(($ai_generarcontable=="1")&&($as_estcontable=="1"))
			{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
				$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);			
				$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
				$io_ds_scgcuentas->insertRow("debhab","D");			
				$io_ds_scgcuentas->insertRow("estatus","A");			
				$io_ds_scgcuentas->insertRow("mondeb",$li_monimp);			
				$io_ds_scgcuentas->insertRow("monhab","0");			
				$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
			}
			$io_ds_cargos->insertRow("codcar",$ls_codcar);			
			$io_ds_cargos->insertRow("baseimp",$li_baseimp);			
			$io_ds_cargos->insertRow("monimp",$li_monimp);			
			$io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
			$io_ds_cargos->insertRow("codpro",$ls_codpro);			
			$io_ds_cargos->insertRow("estcla",$ls_estcla);			
			$io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
			$io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
			$io_ds_cargos->insertRow("cargo",$ls_cargo);			
			$io_ds_cargos->insertRow("original",$li_monimp);			
			$io_ds_cargos->insertRow("formula",$ls_formula);			
			$io_ds_cargos->insertRow("porcar",$ls_porcar);			
			$io_ds_cargos->insertRow("procededoc",$ls_procede);			
			$io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);			
		}
		if((array_key_exists("cargos",$_SESSION))&&($ai_totrowcargos==0))
		{
			unset($_SESSION["cargos"]);
		}
		if($ai_totrowcargos>0)
		{
			$_SESSION["cargos"]=$io_ds_cargos->data;
		}
		$io_ds_cargos->group_by(array('0'=>'nrocomp','1'=>'codpro','2'=>'estcla','3'=>'cuenta','4'=>'codfuefin'),array('0'=>'monimp'),'monimp');
		$li_totrow=$io_ds_cargos->getRowCount('codpro');	
		for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
		{
			$ls_nrocomp=$io_ds_cargos->getValue("nrocomp",$li_fila);
			$ls_codpro=$io_ds_cargos->getValue("codpro",$li_fila);
			$ls_estcla=$io_ds_cargos->getValue("estcla",$li_fila);
			$ls_cuenta=$io_ds_cargos->getValue("cuenta",$li_fila);
			$ls_sccuenta=$io_ds_cargos->getValue("sccuenta",$li_fila);
			$ls_cargo=$io_ds_cargos->getValue("cargo",$li_fila);
			$ls_procede=$io_ds_cargos->getValue("procededoc",$li_fila);
			$li_original=$io_ds_cargos->getValue("original",$li_fila);
			$li_moncue=$io_ds_cargos->getValue("monimp",$li_fila);
			$ls_codfuefin=$io_ds_cargos->getValue("codfuefin",$li_fila);
			
			if ($ls_confiva!="C")
			   {// Caso de IVA Presupuestario, llenamos el datastored de las cuentas presupuestarias
				 $io_ds_spgcuentas->insertRow("spgnrocomp",$ls_nrocomp);			
				 $io_ds_spgcuentas->insertRow("spgcuenta",$ls_cuenta);			
				 $io_ds_spgcuentas->insertRow("spgmonto",$li_moncue);			
				 $io_ds_spgcuentas->insertRow("codpro",$ls_codpro);			
				 $io_ds_spgcuentas->insertRow("estcla",$ls_estcla);			
				 $io_ds_spgcuentas->insertRow("cargo",$ls_cargo);			
				 $io_ds_spgcuentas->insertRow("original",$li_original);			
				 $io_ds_spgcuentas->insertRow("spgsccuenta",$ls_sccuenta);			
				 $io_ds_spgcuentas->insertRow("spgprocededoc",$ls_procede);			
				 $io_ds_spgcuentas->insertRow("codfuefin",$ls_codfuefin);
			   }
		}
		unset($io_ds_cargos);
	}// end function uf_print_cuentas_presupuesto_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_ajustar_cuentas_presupuesto_cargos($as_estcontable,$ai_generarcontable,$ai_totrowcargos)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_ajustar_cuentas_presupuesto_cargos
		//		   Access: private
		//	    Arguments: as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//				   ai_totrowcargos // Total de cargos seleccionados
		//	  Description: Método que ajusta e imprime el grid de las cuentas presupuestarias de los cargos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_scgcuentas, $io_ds_cargos, $io_ds_aux, $io_ds_spgcuentas,$ls_confiva;

		$ls_compromiso=$io_funciones_cxp->uf_obtenervalor("compromiso","");
		$io_ds_cargos->reset_ds();
		$io_ds_aux->reset_ds();
		if(array_key_exists("cargos",$_SESSION))
		{
			$io_ds_cargos->data=$_SESSION["cargos"];
		}
		// Recorrido del Grid de cargos
		for($li_fila=1;$li_fila<=$ai_totrowcargos;$li_fila++)
		{
			$ls_nrocomp=trim($ls_compromiso);
			$ls_codpro=trim($io_funciones_cxp->uf_obtenervalor("codestpro".$li_fila,""));
			$ls_estcla=trim($io_funciones_cxp->uf_obtenervalor("estcla".$li_fila,""));
			$ls_cuenta=trim($io_funciones_cxp->uf_obtenervalor("spgcuenta".$li_fila,""));
			$ls_sccuenta=trim($io_funciones_cxp->uf_obtenervalor("sccuenta".$li_fila,""));
			$ls_formula=trim($io_funciones_cxp->uf_obtenervalor("formula".$li_fila,""));
			$ls_porcar=trim($io_funciones_cxp->uf_obtenervalor("porcar".$li_fila,""));
			$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("procededoc".$li_fila,"CXPRCD"));
			$ls_codfuefin=trim($io_funciones_cxp->uf_obtenervalor("codfuefin".$li_fila,"--"));
			$ls_cargo="1";
			$ls_codcar=trim($io_funciones_cxp->uf_obtenervalor("txtcodcar".$li_fila,""));
			$li_baseimp=trim($io_funciones_cxp->uf_obtenervalor("txtbaseimp".$li_fila,""));
			$li_baseimp=str_replace(".","",$li_baseimp);
			$li_baseimp=str_replace(",",".",$li_baseimp);							
			$li_monimp=trim($io_funciones_cxp->uf_obtenervalor("txtmonimp".$li_fila,"0.00"));
			$li_monimp=str_replace(".","",$li_monimp);
			$li_monimp=str_replace(",",".",$li_monimp);	
			if(($ai_generarcontable=="1")&&($as_estcontable=="1"))
			{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
				$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);			
				$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
				$io_ds_scgcuentas->insertRow("debhab","D");			
				$io_ds_scgcuentas->insertRow("estatus","A");			
				$io_ds_scgcuentas->insertRow("mondeb",$li_monimp);			
				$io_ds_scgcuentas->insertRow("monhab","0");			
				$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
			}
			$io_ds_cargos->insertRow("codcar",$ls_codcar);			
			$io_ds_cargos->insertRow("baseimp",$li_baseimp);			
			$io_ds_cargos->insertRow("monimp",$li_monimp);			
			$io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
			$io_ds_cargos->insertRow("codpro",$ls_codpro);			
			$io_ds_cargos->insertRow("estcla",$ls_estcla);			
			$io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
			$io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
			$io_ds_cargos->insertRow("cargo",$ls_cargo);			
			$io_ds_cargos->insertRow("original",$li_monimp);			
			$io_ds_cargos->insertRow("formula",$ls_formula);			
			$io_ds_cargos->insertRow("porcar",$ls_porcar);			
			$io_ds_cargos->insertRow("procededoc",$ls_procede);			
			$io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);			

			$io_ds_aux->insertRow("codcar",$ls_codcar);			
			$io_ds_aux->insertRow("baseimp",$li_baseimp);			
			$io_ds_aux->insertRow("monimp",$li_monimp);			
			$io_ds_aux->insertRow("nrocomp",$ls_nrocomp);			
			$io_ds_aux->insertRow("codpro",$ls_codpro);			
			$io_ds_aux->insertRow("estcla",$ls_estcla);			
			$io_ds_aux->insertRow("cuenta",$ls_cuenta);			
			$io_ds_aux->insertRow("sccuenta",$ls_sccuenta);			
			$io_ds_aux->insertRow("cargo",$ls_cargo);			
			$io_ds_aux->insertRow("original",$li_monimp);			
			$io_ds_aux->insertRow("formula",$ls_formula);			
			$io_ds_aux->insertRow("porcar",$ls_porcar);			
			$io_ds_aux->insertRow("procededoc",$ls_procede);			
			$io_ds_aux->insertRow("codfuefin",$ls_codfuefin);			
		}
		if((array_key_exists("cargos",$_SESSION))&&($ai_totrowcargos==0))
		{
			unset($_SESSION["cargos"]);
		}
		if($ai_totrowcargos>0)
		{
			$_SESSION["cargos"]=$io_ds_cargos->data;
		}
		$io_ds_aux->group_by(array('0'=>'nrocomp','1'=>'codpro','2'=>'estcla','3'=>'cuenta','4'=>'codfuefin'),array('0'=>'monimp'),'monimp');
		$li_totrow=$io_ds_aux->getRowCount('codpro');	
		for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
		{
			$ls_nrocomp=$io_ds_aux->getValue('nrocomp',$li_fila);
			$ls_codpro=$io_ds_aux->getValue("codpro",$li_fila);
			$ls_estcla=$io_ds_aux->getValue("estcla",$li_fila);
			$ls_cuenta=$io_ds_aux->getValue("cuenta",$li_fila);
			$ls_sccuenta=$io_ds_aux->getValue("sccuenta",$li_fila);
			$ls_cargo=$io_ds_aux->getValue("cargo",$li_fila);
			$ls_procede=$io_ds_aux->getValue("procededoc",$li_fila);
			$li_original=$io_ds_aux->getValue("original",$li_fila);
			$li_moncue=$io_ds_aux->getValue("monimp",$li_fila);
			$ls_codfuefin=$io_ds_aux->getValue("codfuefin",$li_fila);
			
			if ($ls_confiva!="C")
			   {//Caso de IVA Presupuestario, llenamos el datastored de las cuentas presupuestarias
				 $io_ds_spgcuentas->insertRow("spgnrocomp",$ls_nrocomp);			
				 $io_ds_spgcuentas->insertRow("spgcuenta",$ls_cuenta);			
				 $io_ds_spgcuentas->insertRow("spgmonto",$li_moncue);			
				 $io_ds_spgcuentas->insertRow("codpro",$ls_codpro);			
				 $io_ds_spgcuentas->insertRow("estcla",$ls_estcla);			
				 $io_ds_spgcuentas->insertRow("cargo",$ls_cargo);			
				 $io_ds_spgcuentas->insertRow("original",$li_original);			
				 $io_ds_spgcuentas->insertRow("spgsccuenta",$ls_sccuenta);			
				 $io_ds_spgcuentas->insertRow("spgprocededoc",$ls_procede);			
				 $io_ds_spgcuentas->insertRow("codfuefin",$ls_codfuefin);
			   }
		}
		unset($io_ds_cargos);
	}// end function uf_print_ajustar_cuentas_presupuesto_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_deducciones($ai_totrowdeducciones,$ai_cargardeducciones,$as_estcontable,$ai_generarcontable)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_deducciones
		//		   Access: private
		//	    Arguments: ai_totrowdeducciones // Total de Filas de las deducciones
		//				   ai_cargardeducciones // Cargar Deducciones
		//				   as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//	  Description: Método que carga el datastored de cuentas contables con las cuentas de las deducciones
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_ds_scgcuentas, $io_ds_deducciones,$ls_estretiva;
		if($ai_cargardeducciones=="1")
		{
			if($ai_totrowdeducciones>0)
			{
				$ls_documento=trim($io_funciones_cxp->uf_obtenervalor("documento",""));
				// Recorrido del Grid de Deducciones
				for($li_fila=1;$li_fila<=$ai_totrowdeducciones;$li_fila++)
				{
					$ls_codded=trim($io_funciones_cxp->uf_obtenervalor("txtcodded".$li_fila,""));
					$li_monobjret=trim($io_funciones_cxp->uf_obtenervalor("txtmonobjret".$li_fila,"0,00"));
					$li_monret=trim($io_funciones_cxp->uf_obtenervalor("txtmonret".$li_fila,"0,00"));
					$ls_sccuenta=trim($io_funciones_cxp->uf_obtenervalor("sccuenta".$li_fila,""));
					$ls_porded=trim($io_funciones_cxp->uf_obtenervalor("porded".$li_fila,""));
					$ls_procede=trim($io_funciones_cxp->uf_obtenervalor("procededoc".$li_fila,""));
					$li_iva=$io_funciones_cxp->uf_obtenervalor("iva".$li_fila,"");
					$li_islr=$io_funciones_cxp->uf_obtenervalor("islr".$li_fila,""); 
					$li_monto=str_replace(".","",$li_monret);
					$li_monto=str_replace(",",".",$li_monto);							
					if(($ai_generarcontable=="1")&&($as_estcontable=="1")&&(($li_iva!="1" || $ls_estretiva=="C")&&($li_islr!="1" || $ls_estretiva=="C")))
					{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
						$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_documento);			
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
						$io_ds_scgcuentas->insertRow("debhab","H");			
						$io_ds_scgcuentas->insertRow("estatus","A");			
						$io_ds_scgcuentas->insertRow("mondeb","0");			
						$io_ds_scgcuentas->insertRow("monhab",$li_monto);			
						$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
					}		
					$io_ds_deducciones->insertRow("documento",$ls_documento);			
					$io_ds_deducciones->insertRow("codded",$ls_codded);			
					$io_ds_deducciones->insertRow("monobjret",$li_monobjret);			
					$io_ds_deducciones->insertRow("monret",$li_monret);			
					$io_ds_deducciones->insertRow("sccuenta",$ls_sccuenta);			
					$io_ds_deducciones->insertRow("porded",$ls_porded);			
					$io_ds_deducciones->insertRow("procededoc",$ls_procede);			
					$io_ds_deducciones->insertRow("iva",$li_iva);			
					$io_ds_deducciones->insertRow("islr",$li_islr);			
				}
				$_SESSION["deducciones"]=$io_ds_deducciones->data;
			}
			else
			{
				unset($_SESSION["deducciones"]);
			}
		}
		else
		{
			if(array_key_exists("deducciones",$_SESSION))
			{
				$io_ds_deducciones->data=$_SESSION["deducciones"];
				$li_totrow=$io_ds_deducciones->getRowCount('sccuenta');	
				for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
				{
					$ls_documento=$io_ds_deducciones->getValue("documento",$li_fila);
					$ls_codded=$io_ds_deducciones->getValue("codded",$li_fila);
					$li_monobjret=$io_ds_deducciones->getValue("monobjret",$li_fila);
					$li_monret=$io_ds_deducciones->getValue("monret",$li_fila);
					$ls_sccuenta=$io_ds_deducciones->getValue("sccuenta",$li_fila);
					$ls_procede=$io_ds_deducciones->getValue("procededoc",$li_fila);
					$li_iva=$io_ds_deducciones->getValue("iva",$li_fila);
					$li_islr=$io_ds_deducciones->getValue("islr",$li_fila);
					$li_monto=str_replace(".","",$li_monret);
					$li_monto=str_replace(",",".",$li_monto);							

//					if(($ai_generarcontable=="1")&&($as_estcontable=="1")&&(($li_iva!="1")||($ls_estretiva=="C")||($li_islr!="1")))
					if(($ai_generarcontable=="1")&&($as_estcontable=="1")&&(($li_iva!="1" || $ls_estretiva=="C")&&($li_islr!="1" || $ls_estretiva=="C")))
					{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
						$io_ds_scgcuentas->insertRow("scgnrocomp",$ls_documento);			
						$io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
						$io_ds_scgcuentas->insertRow("debhab","H");			
						$io_ds_scgcuentas->insertRow("estatus","A");			
						$io_ds_scgcuentas->insertRow("mondeb","0");			
						$io_ds_scgcuentas->insertRow("monhab",$li_monto);
						$io_ds_scgcuentas->insertRow("procede",$ls_procede);			
					}	
				}		
			}
		}
	}// end function uf_print_cuentas_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cargos()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cargos
		//		   Access: private
		//	    Arguments: as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//				   ai_totrowcargos // Total de cargos seleccionados
		//				   ai_totrowspg    // Total de filas de presupuesto
		//				   lo_object // arreglo de objetos para las cuentas contables
		//	  Description: Método que elimina los cargos de un comprobante
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_scgcuentas, $io_ds_cargos, $ls_numrecdoc, $io_ds_aux;
		
		$ls_compromiso=$io_funciones_cxp->uf_obtenervalor("compromiso","");
		$ls_procede=$io_funciones_cxp->uf_obtenervalor("procededoc","");
		$io_ds_aux->reset_ds();
		$io_ds_cargos->reset_ds();
		if(array_key_exists("cargos",$_SESSION))
		{
			$io_ds_cargos->data=$_SESSION["cargos"];
		}
		$li_totrow=$io_ds_cargos->getRowCount('codcar');	
		$li_totrowcargos=0;
		// Recorrido del datastored de cargos
		for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
		{
			$ls_nrocomp=trim($io_ds_cargos->getValue('nrocomp',$li_fila));
			$ls_procededoc=trim($io_ds_cargos->getValue('procededoc',$li_fila));
			if(!(($ls_nrocomp==$ls_compromiso)&&($ls_procededoc==$ls_procede)))
			{
				$ls_codcar=trim($io_ds_cargos->getValue("codcar",$li_fila));		
				$li_baseimp=trim($io_ds_cargos->getValue("baseimp",$li_fila));		
				$li_monimp=trim($io_ds_cargos->getValue("monimp",$li_fila));		
				$ls_codpro=trim($io_ds_cargos->getValue("codpro",$li_fila));		
				$ls_estcla=trim($io_ds_cargos->getValue("estcla",$li_fila));		
				$ls_cuenta=trim($io_ds_cargos->getValue("cuenta",$li_fila));		
				$ls_sccuenta=trim($io_ds_cargos->getValue("sccuenta",$li_fila));		
				$ls_cargo=trim($io_ds_cargos->getValue("cargo",$li_fila));	
				$li_original=trim($io_ds_cargos->getValue("original",$li_fila));		
				$ls_formula=trim($io_ds_cargos->getValue("formula",$li_fila));		
				$li_porcar=trim($io_ds_cargos->getValue("porcar",$li_fila));		

				$io_ds_aux->insertRow("codcar",$ls_codcar);			
				$io_ds_aux->insertRow("baseimp",$li_baseimp);			
				$io_ds_aux->insertRow("monimp",$li_monimp);
				$io_ds_aux->insertRow("nrocomp",$ls_nrocomp);										
				$io_ds_aux->insertRow("codpro",$ls_codpro);			
				$io_ds_aux->insertRow("estcla",$ls_estcla);			
				$io_ds_aux->insertRow("cuenta",$ls_cuenta);			
				$io_ds_aux->insertRow("sccuenta",$ls_sccuenta);			
				$io_ds_aux->insertRow("cargo",$ls_cargo);			
				$io_ds_aux->insertRow("original",$li_original);			
				$io_ds_aux->insertRow("formula",$ls_formula);			
				$io_ds_aux->insertRow("porcar",$li_porcar);	
				$io_ds_aux->insertRow("procededoc",$ls_procede);
				$li_totrowcargos=$li_totrowcargos+1;
			}		
		}
		if($li_totrowcargos>0)
		{
			$_SESSION["cargos"]=$io_ds_aux->data;
		}
		else
		{
			unset($_SESSION["cargos"]);
		}
	}// end function uf_delete_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_presupuesto($as_estpresupuestario)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_presupuesto
		//		   Access: private
		//	    Arguments: as_estpresupuestario // estatus presupuestario
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_ds_spgcuentas,$io_funciones_cxp;
		// Titulos el Grid
		$lo_title[1]="Nro. Compromiso";
		$lo_title[2]="Código Programático";
		$lo_title[3]="Estatus";
		$lo_title[4]="Código Estadístico";
		$lo_title[5]="Monto"; 
		$lo_title[6]=" "; 
		//print_r($io_ds_spgcuentas);
		//print "<br><br>";
		$io_ds_spgcuentas->group_by(array('0'=>'spgnrocomp','1'=>'codpro','2'=>'estcla','3'=>'spgcuenta','4'=>'codfuefin'),array('0'=>'spgmonto'),array('0'=>'spgnrocomp','1'=>'codpro','2'=>'estcla','3'=>'spgcuenta','4'=>'codfuefin'));
		//print_r($io_ds_spgcuentas);
		//print "<br><br>";*/
		$li_totrow=$io_ds_spgcuentas->getRowCount('spgcuenta');	
		for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
		{
			$ls_nrocomp=$io_ds_spgcuentas->getValue("spgnrocomp",$li_fila);
			$ls_cuenta=$io_ds_spgcuentas->getValue("spgcuenta",$li_fila);
			$li_moncue=number_format($io_ds_spgcuentas->getValue("spgmonto",$li_fila),2,",",".");
			$ls_codpro=$io_ds_spgcuentas->getValue("codpro",$li_fila);
			$ls_estcla=$io_ds_spgcuentas->getValue("estcla",$li_fila);
			$ls_cargo=$io_ds_spgcuentas->getValue("cargo",$li_fila);
			$li_original=$io_ds_spgcuentas->getValue("original",$li_fila);
			$ls_sccuenta=$io_ds_spgcuentas->getValue("spgsccuenta",$li_fila);
			$ls_procede=$io_ds_spgcuentas->getValue("spgprocededoc",$li_fila);
			$ls_codfuefin=$io_ds_spgcuentas->getValue("codfuefin",$li_fila);
			$ls_tipbieordcom=$io_ds_spgcuentas->getValue("tipbieordcom",$li_fila);
			$ls_estint=$io_ds_spgcuentas->getValue("estint",$li_fila);
			$ls_cuentaint=$io_ds_spgcuentas->getValue("cuentaint",$li_fila);
			$ls_programatica="";
			$io_funciones_cxp->uf_formatoprogramatica($ls_codpro,&$ls_programatica);
			$ls_eliminar="";
			$ls_readonly="readonly";
			$ls_estatus="";
			switch($ls_estcla)
			{
				case "A":
					$ls_estatus="Acción";
					break;
				case "P":
					$ls_estatus="Proyecto";
					break;
			}
			if($ls_cargo=="0")
			{
				switch ($as_estpresupuestario)
				{
					case 1: // Causa
						$ls_eliminar="<a href=javascript:ue_delete_compromiso('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
						$ls_readonly="";
					break;
					
					case 2: // compromete y Causa
						$ls_eliminar="<a href=javascript:ue_delete_spg_cuenta('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
					break;
				}
			}
			$lo_object[$li_fila][1]="<input name=txtspgnrocomp".$li_fila."    type=text id=txtspgnrocomp".$li_fila."   class=sin-borde  style=text-align:center size=20 value='".$ls_nrocomp."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtprogramatica".$li_fila."  type=text id=txtprogramatica".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
			$lo_object[$li_fila][3]="<input name=txtestclaaux".$li_fila."  	  type=text id=txtestclaaux".$li_fila."    class=sin-borde  style=text-align:center size=20 value='".$ls_estatus."' readonly>";
			$lo_object[$li_fila][4]="<input name=txtspgcuenta".$li_fila."     type=text id=txtspgcuenta".$li_fila."    class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
			$lo_object[$li_fila][5]="<input name=txtspgmonto".$li_fila."      type=text id=txtspgmonto".$li_fila."     class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_comprobante('".$li_fila."'); value='".$li_moncue."' ".$ls_readonly.">";
			$lo_object[$li_fila][6]=$ls_eliminar.
									"<input name=txtcodpro".$li_fila."        type=hidden id=txtcodpro".$li_fila."        value='".$ls_codpro."'>".
									"<input name=txtcargo".$li_fila."         type=hidden id=txtcargo".$li_fila."         value='".$ls_cargo."'>".
									"<input name=txtoriginal".$li_fila."      type=hidden id=txtoriginal".$li_fila."      value='".$li_original."'>".
									"<input name=txtestcla".$li_fila."        type=hidden id=txtestcla".$li_fila."        value='".$ls_estcla."'>".
									"<input name=txtspgsccuenta".$li_fila."   type=hidden id=txtspgsccuenta".$li_fila."   value='".$ls_sccuenta."'>".
									"<input name=txtspgprocededoc".$li_fila." type=hidden id=txtspgprocededoc".$li_fila." value='".$ls_procede."'>".
									"<input name=txtcodfuefin".$li_fila."     type=hidden id=txtcodfuefin".$li_fila."     value='".$ls_codfuefin."'>".
									"<input name=txttipbieordcom".$li_fila."  type=hidden id=txttipbieordcom".$li_fila."  value='".$ls_tipbieordcom."'>".
									"<input name=txtestint".$li_fila."        type=hidden id=txtestint".$li_fila."  value='".$ls_estint."'>".
									"<input name=txtcuentaint".$li_fila."     type=hidden id=txtcuentaint".$li_fila."  value='".$ls_cuentaint."'>";
		}
		$ai_totrowspg=$li_totrow+1;
		$lo_object[$ai_totrowspg][1]="<input name=txtspgnrocomp".$ai_totrowspg."    type=text id=txtspgnrocomp".$ai_totrowspg."   class=sin-borde  style=text-align:center size=20 value='' readonly>";
		$lo_object[$ai_totrowspg][2]="<input name=txtprogramatica".$ai_totrowspg."  type=text id=txtprogramatica".$ai_totrowspg." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$ai_totrowspg][3]="<input name=txtestclaaux".$ai_totrowspg."  	  type=text id=txtestclaaux".$ai_totrowspg."  class=sin-borde  style=text-align:center size=20 value='' readonly>";
		$lo_object[$ai_totrowspg][4]="<input name=txtspgcuenta".$ai_totrowspg."     type=text id=txtspgcuenta".$ai_totrowspg."    class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$ai_totrowspg][5]="<input name=txtspgmonto".$ai_totrowspg."      type=text id=txtspgmonto".$ai_totrowspg."     class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$ai_totrowspg][6]="".
								     "<input name=txtcodpro".$ai_totrowspg."        type=hidden id=txtcodpro".$ai_totrowspg."        value=''>".
									 "<input name=txtcargo".$ai_totrowspg."         type=hidden id=txtcargo".$ai_totrowspg."         value=''>".
									 "<input name=txtoriginal".$ai_totrowspg."      type=hidden id=txtoriginal".$ai_totrowspg."      value=''>".
									 "<input name=txtestcla".$ai_totrowspg."        type=hidden id=txtestcla".$ai_totrowspg."        value=''>".
									 "<input name=txtspgsccuenta".$ai_totrowspg."   type=hidden id=txtspgsccuenta".$ai_totrowspg."   value=''>".
									 "<input name=txtspgprocededoc".$ai_totrowspg." type=hidden id=txtspgprocededoc".$ai_totrowspg." value=''>".
									 "<input name=txtcodfuefin".$ai_totrowspg."     type=hidden id=txtcodfuefin".$ai_totrowspg."     value=''>".
									 "<input name=txttipbieordcom".$ai_totrowspg."  type=hidden id=txttipbieordcom".$ai_totrowspg."    value=''>".
									 "<input name=txtestint".$ai_totrowspg."        type=hidden id=txtestint".$ai_totrowspg."        value=''>".
									 "<input name=txtcuentaint".$ai_totrowspg."     type=hidden id=txtcuentaint".$ai_totrowspg."     value=''>";
		print "  <table width='870' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		switch ($as_estpresupuestario)
		{
			case 1: // Causa
				print "<td  align='left'><a href='javascript:ue_catalogo_compromisos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Compromisos'>Agregar Compromisos</a>&nbsp;&nbsp;<a href='javascript:ue_catalogo_amortizacion();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Compromisos'>Agregar Amortizacion</a></td>";
			break;
			
			case 2: // compromete y Causa
				print "<td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Presupuestaria</a>&nbsp;&nbsp;</td>";
			break;
		}
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($ai_totrowspg,$lo_title,$lo_object,870,"Cuentas Presupuestarias","gridcuentas");
	}// end function uf_print_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_contable($as_estcontable,$ai_generarcontable)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cuentas_contable
		//		   Access: private
		//	    Arguments: as_estcontable  // estatus contable
		//				   ai_generarcontable  // Generar asiento contable automático 
		//	  Description: Método que imprime el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid,$io_funciones_cxp,$io_ds_scgcuentas,$ls_numrecdoc,$li_cerrarasiento,$io_ds_cargos,$ls_confiva;
				
		// Titulos el Grid
		$lo_title[1]="Nro. Compromiso";//print_r($io_ds_cargos);
		$lo_title[2]="Código Contable";
		$lo_title[3]="Debe";
		$lo_title[4]="Haber"; 
		$lo_title[5]=" "; 
		$li_total=0;
		$li_totaldebe=0;
		$li_totalhaber=0;
		if ($ls_confiva=="C")
		   {// Caso de IVA Contable
			 if (array_key_exists("cargos",$_SESSION))
			    {
				  $io_ds_cargos->data=$_SESSION["cargos"];
				  if ($li_cerrarasiento)
				     {
					   $li_totrow = $io_ds_cargos->getRowCount('nrocomp');
					   for ($li_i=1;$li_i<=$li_totrow;$li_i++)
					       {
							 $ls_nrocomp  = $io_ds_cargos->getValue("nrocomp",$li_i);			
							 $ls_sccuenta = trim($io_ds_cargos->getValue("cuenta",$li_i));
							 $li_monimp   = $io_ds_cargos->getValue("original",$li_i);			
							 $ls_procede  = $io_ds_cargos->getValue("procededoc",$li_i);	
									
							 $io_ds_scgcuentas->insertRow("scgnrocomp",$ls_nrocomp);			
							 $io_ds_scgcuentas->insertRow("scgcuenta",$ls_sccuenta);			
							 $io_ds_scgcuentas->insertRow("debhab","D");			
							 $io_ds_scgcuentas->insertRow("estatus","A");			
							 $io_ds_scgcuentas->insertRow("mondeb",$li_monimp);			
							 $io_ds_scgcuentas->insertRow("monhab","0");			
							 $io_ds_scgcuentas->insertRow("procede",$ls_procede);
					       }
				     }
			    }
		   }
		$io_ds_scgcuentas->group_by(array('0'=>'scgnrocomp','1'=>'scgcuenta','2'=>'debhab','3'=>'estatus'),array('0'=>'mondeb','1'=>'monhab'),'mondeb');
		$li_totrow=$io_ds_scgcuentas->getRowCount('scgnrocomp');	
        if ($li_totrow>1)
		   {
			 $io_ds_scgcuentas->sortData('debhab');
		   }
		for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
		{
			$ls_nrocomp=trim($io_ds_scgcuentas->getValue("scgnrocomp",$li_fila));
			$ls_cuenta=trim($io_ds_scgcuentas->getValue("scgcuenta",$li_fila));
			$ls_debhab=trim($io_ds_scgcuentas->getValue("debhab",$li_fila));
			$ls_estatus=$io_ds_scgcuentas->getValue("estatus",$li_fila);
			$ls_procede=$io_ds_scgcuentas->getValue("procede",$li_fila);			
			$ls_formato="";
			if($ls_debhab=="D")
			{
				$ls_formato="sin-borde";
				$li_mondeb=number_format($io_ds_scgcuentas->getValue("mondeb",$li_fila),2,",",".");
				$li_monhab="";
				$li_totaldebe=$li_totaldebe+$io_ds_scgcuentas->getValue("mondeb",$li_fila);
				$li_total=$li_total+$io_ds_scgcuentas->getValue("mondeb",$li_fila);
			}
			else
			{
				$ls_formato="celdas-azules";
				$li_mondeb="";
				$li_monhab=number_format($io_ds_scgcuentas->getValue("monhab",$li_fila),2,",",".");
				$li_totalhaber=$li_totalhaber+$io_ds_scgcuentas->getValue("monhab",$li_fila);
				$li_total=$li_total-$io_ds_scgcuentas->getValue("monhab",$li_fila);
			}
			$ls_eliminar="";
			if((($ls_estatus=="M")&&($ai_generarcontable=="0"))||($li_cerrarasiento=="1"))
			{
				if($ls_estatus=="M")
				{
					$ls_eliminar="<a href=javascript:ue_delete_scg_cuenta('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
				}
			}
			$lo_object[$li_fila][1]="<input name=txtscgnrocomp".$li_fila."    type=text id=txtscgnrocomp".$li_fila." class=".$ls_formato." style=text-align:center size=30 value='".$ls_nrocomp."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtscgcuenta".$li_fila."     type=text id=txtscgcuenta".$li_fila."  class=".$ls_formato." style=text-align:center size=60 value='".$ls_cuenta."'  readonly>";
			$lo_object[$li_fila][3]="<input name=txtmondeb".$li_fila."        type=text id=txtmondeb".$li_fila."     class=".$ls_formato." style=text-align:right size=25 value='".$li_mondeb."'   readonly>";
			$lo_object[$li_fila][4]="<input name=txtmonhab".$li_fila."        type=text id=txtmonhab".$li_fila."     class=".$ls_formato." style=text-align:right size=25 value='".$li_monhab."'   readonly>";
			$lo_object[$li_fila][5]=$ls_eliminar.
									"<input name=txtdebhab".$li_fila."        type=hidden id=txtdebhab".$li_fila."        value='".$ls_debhab."'>".
									"<input name=txtestatus".$li_fila."       type=hidden id=txtestatus".$li_fila."       value='".$ls_estatus."'>".
									"<input name=txtscgprocededoc".$li_fila." type=hidden id=txtscgprocededoc".$li_fila." value='".$ls_procede."'>";
		}
		if(($ai_generarcontable=="1")&&($li_totrow>0))
		{
			// Ajustamos la del proveedor
			$ls_sccuentaprov=trim($io_funciones_cxp->uf_obtenervalor("sccuentaprov",""));
			$li_totalhaber=$li_totalhaber+$li_total;
			$li_total=number_format($li_total,2,",",".");
			$lo_object[$li_fila][1]="<input name=txtscgnrocomp".$li_fila."    type=text id=txtscgnrocomp".$li_fila." class=celdas-azules style=text-align:center size=25 value='".$ls_numrecdoc."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtscgcuenta".$li_fila."     type=text id=txtscgcuenta".$li_fila."  class=celdas-azules style=text-align:center size=56 value='".$ls_sccuentaprov."'  readonly>";
			$lo_object[$li_fila][3]="<input name=txtmondeb".$li_fila."        type=text id=txtmondeb".$li_fila."     class=celdas-azules style=text-align:right  size=22 value=''   readonly>";
			$lo_object[$li_fila][4]="<input name=txtmonhab".$li_fila."        type=text id=txtmonhab".$li_fila."     class=celdas-azules style=text-align:right  size=22 value='".$li_total."'   readonly>";
			$lo_object[$li_fila][5]="".
									"<input name=txtdebhab".$li_fila."        type=hidden id=txtdebhab".$li_fila."        value='H'>".
									"<input name=txtestatus".$li_fila."       type=hidden id=txtestatus".$li_fila."       value='A'>".
									"<input name=txtscgprocededoc".$li_fila." type=hidden id=txtscgprocededoc".$li_fila." value='CXPRCD'>";
			$li_fila=$li_fila+1;
		}
		$ai_totrowscg=$li_fila;
		$lo_object[$ai_totrowscg][1]="<input name=txtscgnrocomp".$ai_totrowscg."    type=text id=txtscgnrocomp".$ai_totrowscg." class=sin-borde style=text-align:center size=30 value='' readonly>";
		$lo_object[$ai_totrowscg][2]="<input name=txtscgcuenta".$ai_totrowscg."     type=text id=txtscgcuenta".$ai_totrowscg."  class=sin-borde style=text-align:center size=55 value='' readonly>";
		$lo_object[$ai_totrowscg][3]="<input name=txtmondeb".$ai_totrowscg."        type=text id=txtmondeb".$ai_totrowscg."     class=sin-borde style=text-align:right  size=20 value='' readonly>";
		$lo_object[$ai_totrowscg][4]="<input name=txtmonhab".$ai_totrowscg."        type=text id=txtmonhab".$ai_totrowscg."     class=sin-borde style=text-align:right  size=20 value='' readonly>";
		$lo_object[$ai_totrowscg][5]="".
									 "<input name=txtdebhab".$ai_totrowscg."        type=hidden id=txtdebhab".$ai_totrowscg."        value=''>".
									 "<input name=txtestatus".$ai_totrowscg."       type=hidden id=txtestatus".$ai_totrowscg."       value=''>";
									 "<input name=txtscgprocededoc".$ai_totrowscg." type=hidden id=txtscgprocededoc".$ai_totrowscg." value=''>";
		$ls_boton="";
		if((($ai_generarcontable=="0")&&($as_estcontable=="1"))||($li_cerrarasiento=="1"))
		{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
			print "  <table width='870' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
			print "    <tr>";
			print "		<td  align='left'><a href='javascript:ue_catalogo_cuentas_scg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Contable</a>&nbsp;&nbsp;</td>";
			print "    </tr>";
			print "  </table>";
			if($li_cerrarasiento=="0")
			{
				$ls_boton="          <td width='175' height='22' align='right'><div align='left'><input name='btncerrar' type='button' class='boton' id='btncerrar' value='Cerrar Asiento' onClick='javascript: ue_cerrar_asiento();'></div></td>";
			}
		}
		$io_grid->makegrid($ai_totrowscg,$lo_title,$lo_object,870,"Cuentas Contable","gridcuentas");		
		print "<table width='700' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr>";
		print $ls_boton;
		print "          <td width='175' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "          <td width='175' height='22' align='left'><input name='txttotaldebe'  type='text' id='txttotaldebe' style='text-align:right' value='".number_format($li_totaldebe,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "          <td width='175' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "          <td width='175' height='22' align='left'><input name='txttotalhaber'  type='text' id='txttotalhaber' style='text-align:right' value='".number_format($li_totalhaber,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "        </tr>";
		print "</table>";
		unset($io_ds_scgcuentas);
	}// end function uf_print_cuentas_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_subtotal,$ai_cargos,$ai_total,$ai_deducciones,$ai_totgeneral)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal    // Valor del subtotal
		//				   ai_cargos      // Valor total de los cargos
		//				   ai_total       // Total de la solicitud de pago
		//				   ai_deducciones // Total de deducciones
		//				   ai_totgeneral  // Total General
		//	  Description: Método que imprime los totales de la Recepcion de Documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='870' height='116' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr class='titulo-celdanew'>";
		print "          <td height='22' colspan='4'><div align='center'>Totales</div></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='290' height='13'>&nbsp;</td>";
		print "          <td width='125' height='13' align='left'></td>";
		print "          <td width='355' height='13' align='right'></td>";
		print "          <td width='100' height='13' align='right'>&nbsp;</td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><strong>Subtotal&nbsp;&nbsp;</strong></td>";
		print "          <td height='22'><input name='txtsubtotal'  type='text' id='txtsubtotal' style='text-align:right' value='".number_format($ai_subtotal,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'> ";
		print "          	<input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='ue_catalogocreditos();'> ";
		print "          </td>";
		print "          <td height='22'><input name='txtcargos' type='text' id='txtcargos' style='text-align:right' value='".number_format($ai_cargos,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotal' type='text' id='txttotal' style='text-align:right' value='".number_format($ai_total,2,",",".")."' size='22' maxlength='20' readonly align='right' class='texto-azul' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'>";
		print "          	<input name='btndeducciones' type='button' class='boton' id='btndeducciones' value='Deducciones' onClick='ue_catalogodeducciones();'> ";
		print "			 </td>";
		print "          <td height='22'><input name='txtdeducciones' type='text' id='txtdeducciones' style='text-align:right' value='".number_format($ai_deducciones,2,",",".")."' size='22' maxlength='20' readonly align='right' class='texto-rojo'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotalgener' type='text' id='txttotalgener' style='text-align:right' value='".number_format($ai_totgeneral,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
	}// end function uf_print_total
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_presupuesto($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_estcontable,$as_estpresupuestario)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_presupuesto
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   a_codpro  // Código del Proveedor
		//	    		   as_estcontable  // estatus contable
		//	    		   as_estpresupuestario  // estatus presupuetario
		//	  Description: Método que carga de la base de datos el grid de las cuentas presupuestarias del Gasto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_ds_scgcuentas;
		// Titulos el Grid
		$lo_title[1]="Nro. Compromiso";
		$lo_title[2]="Código Programático";
		$lo_title[3]="Estatus";
		$lo_title[4]="Código Estadístico";
		$lo_title[5]="Monto"; 
		$lo_title[6]=" "; 
		$ls_codpro="";
		$ls_titulo="";
		require_once("sigesp_cxp_c_recepcion.php");
		$io_recepcion=new sigesp_cxp_c_recepcion("../../");
		$rs_data = $io_recepcion->uf_load_spgcuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro);
		$li_fila=0;
		while($row=$io_recepcion->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_nrocomp=trim($row["numdoccom"]);
			$ls_codpro=trim($row["codestpro"]);
			$ls_estcla=trim($row["estcla"]);
			$ls_cuenta=trim($row["spg_cuenta"]);
			$ls_sccuenta=trim($row["sc_cuenta"]);
			$ls_cargo=trim($row["cargo"]);
			$ls_procede=trim($row["procede_doc"]);
			$li_moncue=number_format($row["monto"],2,",",".");
			$ls_codfuefin=trim($row["codfuefin"]);
			$li_original="0";
			$ls_programatica="";
			$io_funciones_cxp->uf_formatoprogramatica($ls_codpro,&$ls_programatica);
			$ls_estatus="";
			switch($ls_estcla)
			{
				case "A":
					$ls_estatus="Acción";
					break;
				case "P":
					$ls_estatus="Proyecto";
					break;
			}
			$ls_eliminar="";
			if($ls_cargo=="")
			{
				switch ($as_estpresupuestario)
				{
					case 1: // Causa
						$ls_eliminar="<a href=javascript:ue_delete_compromiso('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
					break;
					case 2: // compromete y Causa
						$ls_eliminar="<a href=javascript:ue_delete_spg_cuenta('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
					break;
				}
				$ls_cargo="0";
			}
			else
			{
				$ls_cargo="1";
			}
			if($ls_cuenta!="")
			{
				$lo_object[$li_fila][1]="<input name=txtspgnrocomp".$li_fila."    type=text id=txtspgnrocomp".$li_fila."   class=sin-borde  style=text-align:center size=20 value='".$ls_nrocomp."' readonly>";
				$lo_object[$li_fila][2]="<input name=txtprogramatica".$li_fila."  type=text id=txtprogramatica".$li_fila." class=sin-borde  style=text-align:center size=45 value='".$ls_programatica."' readonly>";
				$lo_object[$li_fila][3]="<input name=txtestclaaux".$li_fila."  	  type=text id=txtestclaaux".$li_fila."    class=sin-borde  style=text-align:center size=20 value='".$ls_estatus."' readonly>";
				$lo_object[$li_fila][4]="<input name=txtspgcuenta".$li_fila."     type=text id=txtspgcuenta".$li_fila."    class=sin-borde  style=text-align:center size=25 value='".$ls_cuenta."' readonly>";
				$lo_object[$li_fila][5]="<input name=txtspgmonto".$li_fila."      type=text id=txtspgmonto".$li_fila."     class=sin-borde  style=text-align:right  size=25 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_moncue."' >";
				$lo_object[$li_fila][6]=$ls_eliminar.
										"<input name=txtcodpro".$li_fila."        type=hidden id=txtcodpro".$li_fila."      value='".$ls_codpro."'>".
										"<input name=txtcargo".$li_fila."         type=hidden id=txtcargo".$li_fila."       value='".$ls_cargo."'>".
										"<input name=txtoriginal".$li_fila."      type=hidden id=txtoriginal".$li_fila."    value='".$li_original."'>".
										"<input name=txttipbieordcom".$li_fila."  type=hidden id=txttipbieordcom".$li_fila."  value='-'>".
										"<input name=txtestcla".$li_fila." type=hidden id=txtestcla".$li_fila." value='".$ls_estcla."'>".
										"<input name=txtspgsccuenta".$li_fila."   type=hidden id=txtspgsccuenta".$li_fila." value='".$ls_sccuenta."'>".
										"<input name=txtspgprocededoc".$li_fila." type=hidden id=txtspgprocededoc".$li_fila." value='".$ls_procede."'>".
										"<input name=txtcodfuefin".$li_fila."     type=hidden id=txtcodfuefin".$li_fila."     value='".$ls_codfuefin."'>".
										"<input name=txtestint".$li_fila."        type=hidden id=txtestint".$li_fila."  value='-'>".
										"<input name=txtcuentaint".$li_fila."     type=hidden id=txtcuentaint".$li_fila."  value='-'>";
			}
		}
		$li_fila=$li_fila+1;	
		$lo_object[$li_fila][1]="<input name=txtspgnrocomp".$li_fila."    type=text id=txtspgnrocomp".$li_fila."   class=sin-borde  style=text-align:center size=20 value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtprogramatica".$li_fila."  type=text id=txtprogramatica".$li_fila." class=sin-borde  style=text-align:center size=45 value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtestclaaux".$li_fila."  	  type=text id=txtestclaaux".$li_fila."    class=sin-borde  style=text-align:center size=20 value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtspgcuenta".$li_fila."     type=text id=txtspgcuenta".$li_fila."    class=sin-borde  style=text-align:center size=25 value='' readonly>";
		$lo_object[$li_fila][5]="<input name=txtspgmonto".$li_fila."      type=text id=txtspgmonto".$li_fila."     class=sin-borde  style=text-align:right  size=25 value='' readonly>";
		$lo_object[$li_fila][6]="".
								"<input name=txtcodpro".$li_fila."        type=hidden id=txtcodpro".$li_fila."      value=''>".
								"<input name=txtcargo".$li_fila."         type=hidden id=txtcargo".$li_fila."       value=''>".
								"<input name=txtoriginal".$li_fila."      type=hidden id=txtoriginal".$li_fila."    value=''>".
								"<input name=txtestcla".$li_fila."        type=hidden id=txtestcla".$li_fila."      value=''>".
								"<input name=txtspgsccuenta".$li_fila."   type=hidden id=txtspgsccuenta".$li_fila." value=''>".
								"<input name=txtspgprocededoc".$li_fila." type=hidden id=txtspgprocededoc".$li_fila." value=''>".
								"<input name=txtcodfuefin".$li_fila."     type=hidden id=txtcodfuefin".$li_fila."     value=''>";

		print "  <table width='870' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "    <tr>";
		switch ($as_estpresupuestario)
		{
			case 1: // Causa
				print "<td  align='left'><a href='javascript:ue_catalogo_compromisos();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Compromisos'>Agregar Compromisos</a>&nbsp;&nbsp;</td>";
			break;
			
			case 2: // compromete y Causa
				print "<td  align='left'><a href='javascript:ue_catalogo_cuentas_spg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta</a>&nbsp;&nbsp;</td>";
			break;
		}
		
		print "    </tr>";
		print "  </table>";
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,870,"Cuentas Presupuestarias","gridcuentas");
		unset($rs_data);
		unset($io_recepcion);
	}// end function uf_load_cuentas_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_contables($as_numrecdoc,$as_codtipdoc,$as_cedbene,$a_codpro,$as_estcontable,$as_estpresupuestario,$ai_generarcontable)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_contables
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   a_codpro  // Código del Proveedor
		//	    		   as_estcontable  // estatus contable
		//	    		   as_estpresupuestario  // estatus presupuetario
		//	  Description: Método que carga el datastored de cuentas contables
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_cxp, $io_ds_scgcuentas;
		// Titulos el Grid
		$lo_title[1]="Nro. Compromiso";
		$lo_title[2]="Código Contable";
		$lo_title[3]="Debe";
		$lo_title[4]="Haber"; 
		$lo_title[5]=" "; 
		$li_total=0;
		$li_totaldebe=0;
		$li_totalhaber=0;
		require_once("sigesp_cxp_c_recepcion.php");
		$io_recepcion=new sigesp_cxp_c_recepcion("../../");
		$rs_data = $io_recepcion->uf_load_sccuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$a_codpro);
		$li_fila=0;
		while($row=$io_recepcion->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_nrocomp=trim($row["numdoccom"]);
			$ls_cuenta=trim($row["sc_cuenta"]);
			$ls_debhab=trim($row["debhab"]);
			$ls_estatus=$row["estatus"];
			$ls_procede=trim($row["procede_doc"]);
			$ls_formato="";
			if($ls_debhab=="D")
			{
				$ls_formato="sin-borde";
				$li_mondeb=number_format($row["monto"],2,",",".");
				$li_monhab="";
				$li_totaldebe=$li_totaldebe+$row["monto"];
				$li_total=$li_total+$row["monto"];
				$li_size1 = 30;
				$li_size2 = 55;
				$li_size3 = 20;
				$li_size4 = 20;
			}
			else
			{
				$ls_formato="celdas-azules";
				$li_size1 = 25;
				$li_size2 = 56;
				$li_size3 = 22;
				$li_size4 = 22;				
				$li_mondeb="";
				$li_monhab=number_format($row["monto"],2,",",".");
				$li_totalhaber=$li_totalhaber+$row["monto"];
				$li_total=$li_total-$row["monto"];
			}
			$ls_eliminar="";
			if(($ls_estatus=="M")&&($ai_generarcontable=="0"))
			{
				$ls_eliminar="<a href=javascript:ue_delete_scg_cuenta('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";			
			}
			$lo_object[$li_fila][1]="<input name=txtscgnrocomp".$li_fila."   type=text id=txtscgnrocomp".$li_fila." class=".$ls_formato." style=text-align:center size=".$li_size1." value='".$ls_nrocomp."' readonly>";
			$lo_object[$li_fila][2]="<input name=txtscgcuenta".$li_fila."    type=text id=txtscgcuenta".$li_fila."  class=".$ls_formato." style=text-align:center size=".$li_size2." value='".$ls_cuenta."'  readonly>";
			$lo_object[$li_fila][3]="<input name=txtmondeb".$li_fila."       type=text id=txtmondeb".$li_fila."     class=".$ls_formato." style=text-align:right  size=".$li_size3." value='".$li_mondeb."'   readonly>";
			$lo_object[$li_fila][4]="<input name=txtmonhab".$li_fila."        type=text id=txtmonhab".$li_fila."    class=".$ls_formato." style=text-align:right  size=".$li_size4." value='".$li_monhab."'   readonly>";
			$lo_object[$li_fila][5]=$ls_eliminar.
									"<input name=txtdebhab".$li_fila."        type=hidden id=txtdebhab".$li_fila."      value='".$ls_debhab."'>".
  									"<input name=txtestatus".$li_fila."       type=hidden id=txtestatus".$li_fila."     value='".$ls_estatus."'>".
									"<input name=txtscgprocededoc".$li_fila." type=hidden id=txtscgprocededoc".$li_fila." value='".$ls_procede."'>";
		}
		$li_fila++;
		$lo_object[$li_fila][1]="<input name=txtscgnrocomp".$li_fila."    type=text id=txtscgnrocomp".$li_fila." class=sin-borde style=text-align:center size=".$li_size1." value='' readonly>";
		$lo_object[$li_fila][2]="<input name=txtscgcuenta".$li_fila."     type=text id=txtscgcuenta".$li_fila."  class=sin-borde style=text-align:center size=".$li_size2." value='' readonly>";
		$lo_object[$li_fila][3]="<input name=txtmondeb".$li_fila."        type=text id=txtmondeb".$li_fila."     class=sin-borde style=text-align:right  size=".$li_size3." value='' readonly>";
		$lo_object[$li_fila][4]="<input name=txtmonhab".$li_fila."        type=text id=txtmonhab".$li_fila."     class=sin-borde style=text-align:right  size=".$li_size4." value='' readonly>";
		$lo_object[$li_fila][5]=" ".
								"<input name=txtdebhab".$li_fila."        type=hidden id=txtdebhab".$li_fila."      value=''>".
								"<input name=txtestatus".$li_fila."       type=hidden id=txtestatus".$li_fila."     value=''>".
								"<input name=txtscgprocededoc".$li_fila." type=hidden id=txtscgprocededoc".$li_fila." value=''>";
		$ls_boton="";
		if(($ai_generarcontable=="0")&&($as_estcontable=="1"))
		{// si los asientos contables se generan automáticamente y la recepcion tiene afectación contable
			print "  <table width='870' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
			print "    <tr>";
			print "		<td  align='left'><a href='javascript:ue_catalogo_cuentas_scg();'><img src='../shared/imagebank/tools/nuevo.gif' width='20' height='20' border='0' title='Agregar Cuenta'>Agregar Cuenta Contable</a>&nbsp;&nbsp;</td>";
			print "    </tr>";
			print "  </table>";
			$ls_boton="          <td width='175' height='22' align='right'><div align='left'><input name='btncerrar' type='button' class='boton' id='btncerrar' value='Cerrar Asiento' onClick='javascript: ue_cerrar_asiento();'></div></td>";
		}
		$io_grid->makegrid($li_fila,$lo_title,$lo_object,870,"Cuentas Contable","gridcuentas");
		print "<table width='700' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr>";
		print $ls_boton;
		print "          <td width='175' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "          <td width='175' height='22' align='left'><input name='txttotaldebe'  type='text' id='txttotaldebe' style='text-align:right' value='".number_format($li_totaldebe,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "          <td width='175' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "          <td width='175' height='22' align='left'><input name='txttotalhaber'  type='text' id='txttotalhaber' style='text-align:right' value='".number_format($li_totalhaber,2,",",".")."' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "        </tr>";
		print "</table>";
		unset($io_recepcion);
		unset($rs_data);
	}// end function uf_load_cuentas_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_estcontable,$as_estpresupuestario)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   a_codpro  // Código del Proveedor
		//	    		   as_estcontable  // estatus contable
		//	    		   as_estpresupuestario  // estatus presupuetario
		//	  Description: Método que los cargos asociados a una recepcion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones_cxp, $io_ds_cargos;

		require_once("sigesp_cxp_c_recepcion.php");
		$io_recepcion=new sigesp_cxp_c_recepcion("../../");
		$rs_data = $io_recepcion->uf_load_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro);
		$li_fila=0;
		$io_ds_cargos->reset_ds();
		while($row=$io_recepcion->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_codcar=trim($row["codcar"]);
			$ls_nrocomp=trim($row["numdoccom"]);
			$li_baseimp=$row["monobjret"];
			$li_monimp=$row["monret"];
			$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			$ls_estcla=trim($row["estcla"]);
			$ls_cuenta=trim($row["spg_cuenta"]);
			$ls_formula=$row["formula"];
			$li_porcar=$row["porcar"];			
			$ls_procede=trim($row["procede_doc"]);
			$ls_sccuenta=trim($row["sc_cuenta"]);
			$io_ds_cargos->insertRow("codcar",$ls_codcar);
			$io_ds_cargos->insertRow("baseimp",$li_baseimp);			
			$io_ds_cargos->insertRow("monimp",$li_monimp);			
			$io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
			$io_ds_cargos->insertRow("codpro",$ls_codpro);			
			$io_ds_cargos->insertRow("estcla",$ls_estcla);			
			$io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
			$io_ds_cargos->insertRow("cargo",1);			
			$io_ds_cargos->insertRow("original",$li_monimp);			
			$io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
			$io_ds_cargos->insertRow("formula",$ls_formula);			
			$io_ds_cargos->insertRow("porcar",$li_porcar);			
			$io_ds_cargos->insertRow("procededoc",$ls_procede);			
		}
		if($li_fila>0)
		{
			$_SESSION["cargos"]=$io_ds_cargos->data;
		}
		unset($io_ds_cargos);
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_estcontable,$as_estpresupuestario)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_deducciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   a_codpro  // Código del Proveedor
		//	    		   as_estcontable  // estatus contable
		//	    		   as_estpresupuestario  // estatus presupuetario
		//	  Description: Método que carga las deducciones de una recepcion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_ds_deducciones;
				
		require_once("sigesp_cxp_c_recepcion.php");
		$io_recepcion=new sigesp_cxp_c_recepcion("../../");
		$rs_data = $io_recepcion->uf_load_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro);
		$li_fila=0;
		while($row=$io_recepcion->io_sql->fetch_row($rs_data))	  
		{
			$li_fila=$li_fila+1;
			$ls_documento=$row["numdoccom"];
			$ls_codded=$row["codded"];	
			$li_monobjret=number_format($row["monobjret"],2,",",".");
			$li_monret=number_format($row["monret"],2,",",".");
			$ls_sccuenta=$row["sc_cuenta"];
			$ls_porded=$row["porded"];
			$ls_procede=$row["procede_doc"];			
			$li_iva=$row["iva"];			
			$li_islr=$row["islr"];			
			$io_ds_deducciones->insertRow("documento",$ls_documento);			
			$io_ds_deducciones->insertRow("codded",$ls_codded);			
			$io_ds_deducciones->insertRow("monobjret",$li_monobjret);			
			$io_ds_deducciones->insertRow("monret",$li_monret);			
			$io_ds_deducciones->insertRow("sccuenta",$ls_sccuenta);			
			$io_ds_deducciones->insertRow("porded",$ls_porded);			
			$io_ds_deducciones->insertRow("procededoc",$ls_procede);			
			$io_ds_deducciones->insertRow("iva",$li_iva);			
			$io_ds_deducciones->insertRow("islr",$li_islr);			
		}
		if($li_fila>0)
		{
				$_SESSION["deducciones"]=$io_ds_deducciones->data;
		}		
		unset($io_ds_deducciones);		
	}// end function uf_load_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------
?>