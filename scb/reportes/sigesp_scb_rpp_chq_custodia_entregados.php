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
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');
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
	function uf_print_cabecera($la_data,&$io_pdf)
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
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ls_shade1,$ls_shade2,$li_showheaddings,&$io_pdf)
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

		$la_config=array('showHeadings'=>$li_showheaddings, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol'=>$ls_shade1, // Color de la sombra
						 'shadeCol2'=>$ls_shade2, // Color de la sombra
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('fecemi'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
						               'numdoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'beneficiario'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la 
						 			   'monto'=>array('justification'=>'right','width'=>90), // Justificación 
						 			   'estmov'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'fecvenc'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$la_columnas=array('fecemi'=>'<b>Fecha Emisión</b>',
		                   'numdoc'=>'<b>Documento</b>',
						   'beneficiario'=>'<b>Beneficiario</b>',
						   'monto'=>'<b>Monto</b>',
						   'estmov'=>'<b>Estatus</b>',
						   'fecvenc'=>'<b>Fecha Vcto.</b>');
						   
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_entrega($la_data,$ls_shade1,$ls_shade2,$li_showheaddings,&$io_pdf)
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

		$la_config=array('showHeadings'=>$li_showheaddings, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol'=>$ls_shade1, // Color de la sombra
						 'shadeCol2'=>$ls_shade2, // Color de la sombra
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('fecemi'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la 
						               'beneficiario'=>array('justification'=>'left','width'=>430))); // Justificación y ancho de la 
		$la_columnas=array('fecemi'=>' ',
		                   'beneficiario'=>' ');
						   
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_entrega
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total_monto,&$io_pdf)
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
		                 'documento'=>'<b> Total</b>','monto'=>$ad_total_monto);
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
						 			   'documento'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
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

		$sig_inc     = new sigesp_include();
		$con         = $sig_inc->uf_conectar();
		$io_report   = new sigesp_scb_class_report($con);
		$io_function = new class_funciones() ;
		$io_fecha    = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$ldt_fecdes     = $_GET["fecdes"];
		$ldt_fechas     = $_GET["fechas"];	
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
        $ls_denban      = $_GET["denban"];
		$ls_dencta      = $_GET["dencta"];
		$ls_tiprep      = $_GET["tiprep"];
		$ls_tipbol      = 'Bs.';
		$ls_tiporeporte = 0;
		$ls_tiporeporte = $_GET["tiporeporte"];
		global $ls_tiporeporte;
		if ($ls_tiporeporte==1)
		   {
			 require_once("sigesp_scb_class_reportbsf.php");
			 $io_report = new sigesp_scb_class_reportbsf($con);
			 $ls_tipbol = 'Bs.F.';
		   }
		$ls_tipo_destino=$_GET["tipo_destino"];
		$ls_probendesde=$_GET["probendesde"];
		$ls_probenhasta=$_GET["probenhasta"];
		if($ls_tiprep=='C')
		{	$ls_aux="EN CUSTODIA";	}
		else
		{	$ls_aux="ENTREGADOS";	}
	    $ls_fechades=$io_function->uf_convertirfecmostrar($ldt_fecdes);
	    $ls_fechahas=$io_function->uf_convertirfecmostrar($ldt_fechas);
		
	//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b>LISTADO DE CHEQUES ".$ls_aux." $ls_tipbol</b> "; 
		$ls_fecha="<b> DESDE EL ".$ls_fechades."   HASTA EL  ".$ls_fechahas." </b>";      
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_find_bancos($ls_codban,$ls_ctaban);
	
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->ds_bancos->getRowCount("codban");
        $ld_total_monto_general=0;
		$ls_spg_cuenta_ant="";
		$ld_total_monto=0;
		for($z=1;$z<=$li_tot;$z++)
		{
			$li_tmp=($z+1);
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
		    $ls_codban=$io_report->ds_bancos->getValue("codban",$z);  	  
		    $ls_nomban=$io_report->ds_bancos->getValue("nomban",$z);  	  
		    $ls_ctaban=$io_report->ds_bancos->getValue("ctaban",$z);  	  
		    $ls_dencta=$io_report->ds_bancos->getValue("dencta",$z);  	  
			$la_data[1]=array('name'=>$ls_nomban);
			$la_data[2]=array('name'=>$ls_ctaban."  ".$ls_dencta);
			$li_altousado=$io_pdf->get_alto_usado();
			$lb_valido=$io_report->uf_cargar_cheques_custodia_entregados($ls_fechades,$ls_fechahas,$ls_codban,$ls_ctaban,$ls_probendesde,$ls_probenhasta,$ls_tipo_destino,$ls_tiprep);	 
	    	$li_totdocumentos=$io_report->ds_documentos->getRowCount("codban");
			
			if($li_altousado>230)			
			{
				 $io_pdf->ezNewPage();
			}
			if($li_totdocumentos>0)
			{
				uf_print_cabecera($la_data,$io_pdf); // Imprimimos el detalle 		
				$ld_total_monto=0;
				unset($la_data);
				for($y=1;$y<=$li_totdocumentos;$y++)
				{
					$ld_fecemi     = $io_report->ds_documentos->getValue("fecmov",$y);
					$ld_fecvenc    = $io_report->ds_documentos->getValue("fecvenc",$y);
					$ls_numdoc	   = $io_report->ds_documentos->getValue("numdoc",$y);
					$ls_nomproben  = $io_report->ds_documentos->getValue("nomproben",$y);
					$ldec_monto    = $io_report->ds_documentos->getValue("monto",$y);
					$ls_estmov     = $io_report->ds_documentos->getValue("estmov",$y);
					$ld_numdoc     = $io_report->ds_documentos->getValue("numdoc",$y);
					$li_estentrega = $io_report->ds_documentos->getValue("emicheproc",$y);
					$ls_cedula	   = $io_report->ds_documentos->getValue("emicheced",$y);
					$ls_nombre	   = $io_report->ds_documentos->getValue("emichenom",$y);
					$ld_fecha      = $io_report->ds_documentos->getValue("emichefec",$y);
					$la_data[1]=array('fecemi'=>$ld_fecemi,'numdoc'=>$ls_numdoc,'beneficiario'=>$ls_nomproben,'monto'=>number_format($ldec_monto,2,",","."),'estmov'=>$ls_estmov,'fecvenc'=>$ld_fecvenc);
					if($y==1)
					{$li_showheaddings=1;}
					else
					{$li_showheaddings=0;}
					uf_print_detalle($la_data,array(0.95,0.95,0.95),array(1.5,1.5,1.5),$li_showheaddings,$io_pdf);
					if($li_estentrega==1)
					{
						$la_data_entrega[1]=array('fecemi'=>"Entregado el: ".$ld_fecha,'beneficiario'=>"Entregado a: ".$ls_nombre." C.I.: ".$ls_cedula);
						uf_print_detalle_entrega($la_data_entrega,array(1.5,1.5,1.5),array(0.95,0.95,0.95),0,$io_pdf);
					}
				}							
				unset($la_data);
			}
		}//for		
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
	unset($io_function_report);
?> 