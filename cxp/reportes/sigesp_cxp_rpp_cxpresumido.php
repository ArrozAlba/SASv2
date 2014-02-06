<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Cuentas por Pagar Resumido
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_cxpresumido.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_fechadesde,$as_fechahasta,$as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_periodo="<b>Del :</b>".$as_fechadesde."   "."<b>Al :</b>".$as_fechahasta;	
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(250,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText(220,716,11,$ls_periodo); // Agregar el título
		$io_pdf->addText(500,765,9,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalleproveedores($la_datapro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalleproveedores
		//		   Access: private 
		//	    Arguments: la_datapro // arreglo de información de proveedores
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]= array('codigo'=>"<b>Codigo</b>",'nombre'=>"<b>Proveedor</b>",'saldoant'=>"<b>Saldo Anterior</b>",'mondeb'=>"<b>Debitos</b>",'monhab'=>"<b>Creditos</b>",'saldo'=>"<b>Saldo Actual</b>");
		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tamaño de Letras
						 'titleFontSize' =>8,// Tamaño de Letras de los títulos
						 'showLines'=>1,     // Mostrar Líneas
						 'shaded'=>0,        // Sombra entre líneas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>190),
									   'saldoant'=>array('justification'=>'center','width'=>80),
									   'mondeb'=>array('justification'=>'center','width'=>80),
									   'monhab'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'Proveedores',$la_config);

		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tamaño de Letras
						 'titleFontSize' =>8,// Tamaño de Letras de los títulos
						 'showLines'=>0,     // Mostrar Líneas
						 'shaded'=>0,        // Sombra entre líneas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190),
									   'saldoant'=>array('justification'=>'right','width'=>80),
									   'mondeb'=>array('justification'=>'right','width'=>80),
									   'monhab'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_datapro,$la_columna,'',$la_config);
	}// end function uf_print_detalleproveedores
	//--------------------------------------------------------------------------------------------------------------------------------
     
	function uf_print_detallebeneficiarios($la_databen,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: la_databen // arreglo de información de Beneficiarios
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]= array('codigo'=>"<b>Codigo</b>",'nombre'=>"<b>Beneficiario</b>",'saldoant'=>"<b>Saldo Anterior</b>",'mondeb'=>"<b>Debitos</b>",'monhab'=>"<b>Creditos</b>",'saldo'=>"<b>Saldo Actual</b>");
		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tamaño de Letras
						 'titleFontSize' =>8,// Tamaño de Letras de los títulos
						 'showLines'=>1,     // Mostrar Líneas
						 'shaded'=>0,        // Sombra entre líneas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>190),
									   'saldoant'=>array('justification'=>'center','width'=>80),
									   'mondeb'=>array('justification'=>'center','width'=>80),
									   'monhab'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'Beneficiarios',$la_config);

		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tamaño de Letras
						 'titleFontSize' =>8,// Tamaño de Letras de los títulos
						 'showLines'=>0,     // Mostrar Líneas
						 'shaded'=>0,        // Sombra entre líneas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190),
									   'saldoant'=>array('justification'=>'right','width'=>80),
									   'mondeb'=>array('justification'=>'right','width'=>80),
									   'monhab'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_databen,$la_columna,'',$la_config);
	}// end function uf_print_detallebeneficiarios
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalesproveedor($ai_totsalantp,$ai_totmondebp,$ai_totmonhabp,$ai_totsalactp,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Función que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		if ($ai_totsalantp<0)
		{
			$ai_totsalantp= abs($ai_totsalantp);
			$ai_totsalantp= number_format($ai_totsalantp,2,',','.');
			$ai_totsalantp= '('.$ai_totsalantp.')';
		}
		else
		{
			$ai_totsalantp= number_format($ai_totsalantp,2,',','.');
		}
		if ($ai_totsalactp<0)
		{
			$ai_totsalactp= abs($ai_totsalactp);
			$ai_totsalactp= number_format($ai_totsalactp,2,',','.');
			$ai_totsalactp= '('.$ai_totsalactp.')';
		}
		else
		{
			$ai_totsalactp= number_format($ai_totsalactp,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL '.$ls_titulo.'</b>','totsalantp'=>$ai_totsalantp,'totmondebp'=>$ai_totmondebp,'totmonhabp'=>$ai_totmonhabp,'totsalactp'=>$ai_totsalactp));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tamaño de Letras
						 'titleFontSize' =>8,   // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantp'=>array('justification'=>'right','width'=>80),
									   'totmondebp'=>array('justification'=>'right','width'=>80),
									   'totmonhabp'=>array('justification'=>'right','width'=>80),
									   'totsalactp'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantp'=>'','totmondebp'=>'','totmonhabp'=>'','totsalactp'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalesbeneficiario($ai_totsalantb,$ai_totmondebb,$ai_totmonhabb,$ai_totsalactb,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Función que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		if ($ai_totsalantb<0)
		{
			$ai_totsalantb= abs($ai_totsalantb);
			$ai_totsalantb= number_format($ai_totsalantb,2,',','.');
			$ai_totsalantb= '('.$ai_totsalantb.')';
		}
		else
		{
			$ai_totsalantb= number_format($ai_totsalantb,2,',','.');
		}
		if ($ai_totsalactb<0)
		{
			$ai_totsalactb= abs($ai_totsalactb);
			$ai_totsalactb= number_format($ai_totsalactb,2,',','.');
			$ai_totsalactb= '('.$ai_totsalactb.')';
		}
		else
		{
			$ai_totsalactb= number_format($ai_totsalactb,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL '.$ls_titulo.'</b>','totsalantb'=>$ai_totsalantb,'totmondebb'=>$ai_totmondebb,'totmonhabb'=>$ai_totmonhabb,'totsalactb'=>$ai_totsalactb));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tamaño de Letras
						 'titleFontSize' =>8,   // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantb'=>array('justification'=>'right','width'=>80),
									   'totmondebb'=>array('justification'=>'right','width'=>80),
									   'totmonhabb'=>array('justification'=>'right','width'=>80),
									   'totsalactb'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantb'=>'','totmondebb'=>'','totmonhabb'=>'','totsalactb'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_dsctasxpagar= new class_datastore();
	$io_dsctasxpagar = new class_datastore();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo= "<b>Cuentas Por Pagar</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$li_excluir=$io_fun_cxp->uf_obtenervalor_get("excluir",0);
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	$lb_valido= $io_report->uf_select_solicitudes("","","",$ld_fecemides,$ld_fecemihas);
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	if (!$lb_valido) // Existe algún error ó no hay registros.
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar ');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{

		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait');                       // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3);                            // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ld_fecemides,$ld_fecemihas,$ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,8,'','',1); // Insertar el número de página
		$li_totrow= $io_report->DS->getRowCount('tipproben');
		$li_p=0;
		$li_q=0;
		$li_totgendeb=0;
		$li_totgenhab=0;
		$li_totgensal=0;
		$li_totmondebp=0;
		$li_totmonhabp=0;
		$li_totmondebb=0;
		$li_totmonhabb=0;
		$li_totsalantp=0;
		$li_totsalactp=0;
		$li_totsalantb=0;
		$li_totsalactb=0;
		$la_databen="";
		$la_datapro="";
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$li_salsol=0;
			$li_totaldebe=0;
			$li_totalhaber=0;
			$li_totalsaldo=0;
			$ls_tipproben= $io_report->DS->getValue('tipproben',$li_i);
			$ls_cedbene= $io_report->DS->getValue('ced_bene',$li_i);
			$ls_codpro= $io_report->DS->getValue('cod_pro',$li_i);
			$ls_nombre= $io_report->DS->getValue('nombre',$li_i);
			if($ls_tipproben=="B")
			{
				$ls_codigo=$ls_cedbene;
			}
			else
			{
				$ls_codigo=$ls_codpro;
			}
			if($lb_valido)
			{
				$li_monsolpre=0;
//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudes_previas($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,
																	  $ld_fecemihas);
				if($lb_valido)
				{
					$li_solcont=0;
					$li_solanul=0;
					$li_totsolprevias= $io_report->ds_solprevias->getRowCount('monsol');
//					print "<br>".$li_totsolprevias."<br>";
					for($li_j=1;$li_j<=$li_totsolprevias;$li_j++)
					{
						$ls_estatus= $io_report->ds_solprevias->getValue('estatus',$li_j);
						$li_monsol= $io_report->ds_solprevias->getValue('monsol',$li_j);
						$ls_numsolaux= $io_report->ds_solprevias->getValue('numsol',$li_j);
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolaux);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						switch ($ls_estatus)
						{
							case "C":
								$li_solcont=($li_solcont+$li_monsol);
							break;
							case "A":
								$li_solanul=($li_solanul+$li_monsol);
							break;
						}
					}
					$li_monsolpre=($li_solcont-$li_solanul);
				}
				$li_monpagpre=0;
				$lb_valido= $io_report->uf_select_pagosprevios($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
															   $li_monpagpre,$li_monretpre);
																			   
				if($ls_estretiva=="B")
				{
					$li_monpagpre=$li_monpagpre+$li_monretpre;
				}
				$li_monsalant=($li_monsolpre-$li_monpagpre);
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudesactualescxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,
																	     $ld_fecemihas);
				if($lb_valido)
				{
					$li_salsol=$li_monsalant;
					$li_totsolact= $io_report->ds_solactuales->getRowCount('numsol');
					for($li_j=1;$li_j<=$li_totsolact;$li_j++)
					{
						$ls_numsol= $io_report->ds_solactuales->getValue('numsol',$li_j);
						$ls_estprodoc= $io_report->ds_solactuales->getValue('estprodoc',$li_j);
						$ls_consol= $io_report->ds_solactuales->getValue('consol',$li_j);
						$li_monsol= $io_report->ds_solactuales->getValue('monsol',$li_j); //Monto de la Solicitudes de Pago actuales.
						$ld_fecsol= $io_report->ds_solactuales->getValue('fecha',$li_j);
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						$li_salsol= $li_salsol+$li_monsol;
						$li_totalhaber=$li_totalhaber+$li_monsol;
						$ld_fecsol=$io_funciones->uf_convertirfecmostrar($ld_fecsol);
						$ls_monto= number_format($li_monsol,2,',','.');
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						$la_datasol[$li_j]= array('numsol'=>$ls_numsol,'consol'=>$ls_consol,'procedencia'=>"",'fecha'=>$ld_fecsol,'debe'=>"0,00",'haber'=>$ls_monto,'saldo'=>$ls_salsol);
					}
					$io_report->ds_solactuales->reset_ds();
					unset($la_datasol);
//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
					$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas,"");
					if($lb_valido)
					{
						$li_totndnc= $io_report->ds_detndnc->getRowCount('numsol');
						for($li_j=1;$li_j<=$li_totndnc;$li_j++)
						{
							$ls_numdc= $io_report->ds_detndnc->getValue('numdc',$li_j);
							$ls_codope= $io_report->ds_detndnc->getValue('codope',$li_j);
							$ls_desope= $io_report->ds_detndnc->getValue('desope',$li_j);
							$li_monto= $io_report->ds_detndnc->getValue('monto',$li_j); //Monto de la Solicitudes de Pago actuales.
							$ld_fecope= $io_report->ds_detndnc->getValue('fecope',$li_j);
							if($ls_codope=="ND")
							{
								$li_salsol= $li_salsol+$li_monto;
								$li_debe=0;
								$li_haber=$li_monto;
								$ls_procedencia="Debito";
								$li_totalhaber=$li_totalhaber+$li_monto;
							}
							else
							{
								$li_salsol= $li_salsol-$li_monto;
								$li_debe=$li_monto;
								$li_haber=0;
								$ls_procedencia="Credito";
								$li_totaldebe=$li_totaldebe+$li_monto;
							}
							$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
							$li_debe= number_format($li_debe,2,',','.');
							$li_haber= number_format($li_haber,2,',','.');
							$li_salsol=round($li_salsol,2);
							if(doubleval($li_salsol)>0)
							{
								$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
							}
							else
							{
								$ls_salsol= abs($li_salsol);
								$ls_salsol= number_format($ls_salsol,2,',','.');
							}
							$la_datandnc[$li_j]= array('numsol'=>$ls_numdc,'consol'=>$ls_desope,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecope,'debe'=>$li_debe,'haber'=>$li_haber,'saldo'=>$ls_salsol);
						}
					}
//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
					unset($la_datapag);
					$lb_valido=$io_report->uf_select_informacionpagoscxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,
																		 $ld_fecemihas);
					if($lb_valido)
					{
						$li_totpagact= $io_report->ds_pagactuales->getRowCount('numsol');
						for($li_j=1;$li_j<=$li_totpagact;$li_j++)
						{
							$ls_salsol="";
							$ls_numsol= $io_report->ds_pagactuales->getValue('numsol',$li_j);
							$ls_codope= $io_report->ds_pagactuales->getValue('codope',$li_j);
							$ls_conmov= $io_report->ds_pagactuales->getValue('conmov',$li_j);
							$li_monto= $io_report->ds_pagactuales->getValue('monto',$li_j); //Monto de la Solicitudes de Pago actuales.
							$ld_fecmov= $io_report->ds_pagactuales->getValue('fecmov',$li_j);
							if($ls_estretiva=="B")
							{
								$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
								$li_monto=$li_monto+$li_monretiva;
							}
							$li_salsol= $li_salsol-$li_monto;
							$li_totaldebe=$li_totaldebe+$li_monto;
							$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
							$li_salsol=round($li_salsol,2);
							if(doubleval($li_salsol)>0)
							{
								$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
							}
							else
							{
								$ls_salsol= abs($li_salsol);
								$ls_salsol= number_format($ls_salsol,2,',','.');
							}
							if($ls_codope=="CH")
							{
								$ls_procedencia="Cheque";
							}
							$ls_monto= number_format($li_monto,2,',','.');
							$la_datapag[$li_j]= array('numsol'=>$ls_numsol,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ls_monto,'haber'=>"0,00",'saldo'=>$ls_salsol);
						}
//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
					}

				}
			}
			else
			{
				break;
			}
			$li_totalsaldo=$li_salsol;
			/*$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;*/
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
/*			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
*/			if(($li_excluir==0)||($li_salsol>0))
			{
					if($ls_tipproben=="P")
					{
						$li_p++;
						$li_totsalantp= $li_totsalantp + $li_monsalant;
						$li_totsalactp= $li_totsalactp + $li_salsol;
						$li_totmondebp= $li_totmondebp + $li_totaldebe;
						$li_totmonhabp= $li_totmonhabp + $li_totalhaber;
						$li_montodebe= number_format($li_totaldebe,2,',','.');
						$li_montohaber= number_format($li_totalhaber,2,',','.');
						$la_datapro[$li_p]= array('codigo'=>$ls_codigo,'nombre'=>$ls_nombre,'saldoant'=>$ls_saldoanterior,'mondeb'=>$li_montodebe,'monhab'=>$li_montohaber,'saldo'=>$li_totalsaldo);
					}
					else
					{
						$li_q++;
						$li_totsalantb= $li_totsalantb + $li_monsalant;
						$li_totsalactb= $li_totsalactb + $li_totgensal;
						$li_totmondebb= $li_totmondebb + $li_totgendeb;
						$li_totmonhabb= $li_totmonhabb + $li_totgenhab;
						$li_montodebe= number_format($li_totaldebe,2,',','.');
						$li_montohaber= number_format($li_totalhaber,2,',','.');
						$la_databen[$li_q]= array('codigo'=>$ls_codigo,'nombre'=>$ls_nombre,'saldoant'=>$ls_saldoanterior,'mondeb'=>$li_montodebe,'monhab'=>$li_montohaber,'saldo'=>$li_totalsaldo);
					}

			}
			if(!$lb_valido)
			{break;}
		}// fin for uf_select_solicitudes
		$li_totmondebp= number_format($li_totmondebp,2,',','.');
		$li_totmonhabp= number_format($li_totmonhabp,2,',','.');
		$li_totmondebb= number_format($li_totmondebb,2,',','.');
		$li_totmonhabb= number_format($li_totmonhabb,2,',','.');
		if($la_datapro!="")
		{
			uf_print_detalleproveedores($la_datapro,$io_pdf); // Imprimimos el detalle  
			uf_print_totalesproveedor($li_totsalantp,$li_totmondebp,$li_totmonhabp,$li_totsalactp,&$io_pdf);
		}
		if($la_databen!="")
		{
			uf_print_detallebeneficiarios($la_databen,$io_pdf); // Imprimimos el detalle  
			uf_print_totalesbeneficiario($li_totsalantb,$li_totmondebb,$li_totmonhabb,$li_totsalactb,&$io_pdf);
		}
		
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf);
			unset($io_report);
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generarse el Reporte');"); 
			print(" close();");
			print("</script>");
		}
	}
?>