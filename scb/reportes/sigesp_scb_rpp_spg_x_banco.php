<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 26/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título		
		$li_tm=$io_pdf->getTextWidth(10,$as_fecha);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el título
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomban,$as_cta,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 26/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Banco</b> '.$as_nomban.' '),
		               array('name'=>'<b>Cuenta</b> '.$as_cta.' ' ));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 26/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('numdoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						               'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la 
						 			   'beneficiario'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la 
						 			   'concepto'=>array('justification'=>'left','width'=>150), // Justificación 
						 			   'codope'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$la_columnas=array('numdoc'=>'<b>Documento</b>',
		                   'fecha'=>'<b>Fecha</b>',
						   'beneficiario'=>'<b>Beneficiario</b>',
						   'concepto'=>'<b>Concepto</b>',
						   'codope'=>'<b>Operación</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuenta($ls_estructura, $ls_cuenta, $ls_denominacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cuenta
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime las cuentas presupuestarias
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 04/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->add_lineas(1);
		 $la_data[0]["1"]="<b>Estructura Presupuestaria</b>";
		 $la_data[0]["2"]="<b>Cuenta</b>";
		 $la_data[0]["3"]="<b>Denominación</b>";
		 $la_data[1]["1"]="<b>$ls_estructura</b>";
		 $la_data[1]["2"]="<b>$ls_cuenta</b>";
		 $la_data[1]["3"]="<b>$ls_denominacion</b>";				
		 $la_anchos_col = array(60,20,114);
		 $la_justificaciones = array("center","center","center");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>0,
							   "color_fondo"=>array(226,237,250),
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(-1,$la_data,$la_opciones);
		 
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total_monto,$ls_palabra,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 26/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'___________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('beneficiario'=>' ','concepto'=>'','fecha'=>'','procede'=>'',
		                 'documento'=>'<b>'.$ls_palabra.'</b>','monto'=>$ad_total_monto);
		$la_columnas=array('beneficiario'=>' ','concepto'=>'','fecha'=>'','procede'=>'','documento'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la 
						               'concepto'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'procede'=>array('justification'=>'center','width'=>50), // Justificación 
						 			   'documento'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("sigesp_scb_class_report.php");
		require_once('../../shared/class_folder/class_pdf.php');
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/sigesp_include.php");
        require_once("../../shared/class_folder/class_funciones.php");
		$sig_inc	 = new sigesp_include();
		$con		 = $sig_inc->uf_conectar();
		$io_report   = new sigesp_scb_class_report($con);
		$io_function = new class_funciones() ;
		$io_fecha    = new class_fecha();
       
	   //--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$ldt_fecdes   = $_GET["txtfecdes"];
		$ldt_fechas   = $_GET["txtfechas"];	
	    $ls_cuentades = $_GET["txtcuentades"];
	    $ls_cuentahas = $_GET["txtcuentahas"];
		$ls_codban	  = $_GET["txtcodban"];
		$ls_ctaban	  = $_GET["txtcuenta"];
        $ls_denban	  = $_GET["txtdenban"];
		$ls_dencta	  = $_GET["txtdencta"];
		$ls_ckbfec	  = $_GET["ckbfec"];
        $ls_ckbpro	  = $_GET["ckbproc"];
        $ls_ckbdoc	  = $_GET["ckbdoc"];
        $ls_ckbbene	  = $_GET["ckbbene"];
		
		$ls_tipbol      = 'Bs.';
		$ls_tiporeporte = 0;
		$ls_tiporeporte = $_GET["tiporeporte"];
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			require_once("sigesp_scb_class_reportbsf.php");
			$io_report = new sigesp_scb_class_reportbsf($con);
			$ls_tipbol = 'Bs.F.';
		}
		
	    $ls_fechades=$io_function->uf_convertirfecmostrar($ldt_fecdes);
	    $ls_fechahas=$io_function->uf_convertirfecmostrar($ldt_fechas);
		
//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b>MOVIMIENTOS PRESUPUESTARIOS POR BANCO $ls_tipbol</b> "; 
		$ls_fecha ="<b> DESDE  ".$ls_fechades."   HASTA LA FECHA  ".$ls_fechahas." </b>";      
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_scb_reportes_presupuesto_x_banco($ldt_fecdes,$ldt_fechas,$ls_cuentades,$ls_cuentahas,$ls_codban,$ls_ctaban,$ls_ckbfec,$ls_ckbpro,$ls_ckbdoc,$ls_ckbbene);
 
	 if($lb_valido==false)// Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar, quizas no existan documentos contabilizados');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new class_pdf('LETTER','portrait');// Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->ds_reporte_final->getRowCount("numdoc");
        $ld_total_monto_general=0;
		$ls_spg_cuenta_ant="";
		$ld_total_monto=0;
		uf_print_cabecera($ls_denban,$ls_ctaban."   ".$ls_dencta,$io_pdf);
		$ls_codespro=$io_report->ds_reporte_final->getValue("codestpro",1);
		$ls_spg_cuenta=$io_report->ds_reporte_final->getValue("spg_cuenta",1);
		$ls_denominacion=$io_report->ds_reporte_final->getValue("denominacion",1);
		uf_print_cuenta($ls_codespro, $ls_spg_cuenta, $ls_denominacion,$io_pdf);
		$ls_codestpro="";
		$i=0;
		for($z=1;$z<=$li_tot;$z++)
		{
			$li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum    = $io_pdf->ezPageCount;
			$i++;
		    $ls_nom_benef   = $io_report->ds_reporte_final->getValue("nomproben",$z);  	  
			$ldt_fecha      = $io_report->ds_reporte_final->getValue("fecha",$z); 
			$ldt_fecha      = $io_function->uf_convertirfecmostrar($ldt_fecha);
			$ls_descripcion = $io_report->ds_reporte_final->getValue("conmov",$z);
			$ls_codope      = $io_report->ds_reporte_final->getValue("codope",$z);
			$ls_numdoc      = $io_report->ds_reporte_final->getValue("numdoc",$z);
			$ld_monto		= $io_report->ds_reporte_final->getValue("monto",$z); 						
			$ld_total_monto_general = $ld_total_monto_general+$ld_monto;
			if(($ls_codespro!= $io_report->ds_reporte_final->getValue("codestpro",$z)) || ($ls_spg_cuenta!=$io_report->ds_reporte_final->getValue("spg_cuenta",$z)))
			{
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera(number_format($ld_total_monto,2,",","."),"Total",$io_pdf);
				$ls_codespro=$io_report->ds_reporte_final->getValue("codestpro",$z);
				$ls_spg_cuenta=$io_report->ds_reporte_final->getValue("spg_cuenta",$z);
				$ls_denominacion=$io_report->ds_reporte_final->getValue("denominacion",$z);
				uf_print_cuenta($ls_codespro, $ls_spg_cuenta, $ls_denominacion,$io_pdf);			
				$ld_total_monto=0;
				$la_data=array();
				$i=0;
			}
			$ld_total_monto = $ld_total_monto+$ld_monto;
			$ld_monto		= number_format($ld_monto,2,",",".");			
			$la_data[$i]=array('numdoc'=>$ls_numdoc,'fecha'=>$ldt_fecha,'beneficiario'=>$ls_nom_benef,'concepto'=>$ls_descripcion,
			                    'codope'=>$ls_codope,'monto'=>$ld_monto);				 
			
	    }//for
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_pie_cabecera(number_format($ld_total_monto,2,",","."),"Total",$io_pdf);
		uf_print_pie_cabecera(number_format($ld_total_monto_general,2,",","."),"Total General",$io_pdf);
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 