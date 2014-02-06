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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/08/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_comprobante_formato1.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/08/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],15,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$as_nombre=$_SESSION["la_empresa"]["nombre"];
		$li_tm=$io_pdf->getTextWidth(9,$as_nombre);
		$tm=310-($li_tm/2);
		$io_pdf->addText($tm,765,9,$as_nombre); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
		$tm=310-($li_tm/2);
		$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el título

		$li_tm=$io_pdf->getTextWidth(9,$as_fecha);
		$tm=310-($li_tm/2);
		$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el fecha
		
		$io_pdf->addText(530,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(530,730,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(530,720,8,date("h:i a")); // Agregar la Fecha
		
		
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera de tabla
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/08/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(700);
		$io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(25,685,555,$io_pdf->getFontHeight(15));
        $io_pdf->setColor(0,0,0);
		
		$la_data1[0]=array('fecha'=>'<b>Fecha</b>',
					       'procede'=>'<b>Procede</b>',
					       'numero'=>'<b>Número</b>',
					       'tipo'=>'<b>Tipo de Comprobante</b>',
					       'monto'=>'<b>Monto Bs.</b>');	
					   
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tab'xPos'=>300, // Orientación de la tabla
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
					     'cols'=>array('fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
								       'procede'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
								       'numero'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
								       'tipo'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
								       'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
	
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/08/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tab'xPos'=>300, // Orientación de la tabla
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
					     'cols'=>array('fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
								       'procede'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
								       'numero'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
								       'tipo'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
								       'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		
		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ad_total,$ad_cont,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 19/08/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>600,//Ancho de la tabla	
						 'xPos'=>320, // Orientación de la tabla				 
						 'maxWidth'=>600); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,'','',$la_config);	
		
		$la_data[0]=array('total'=>'<b>TOTAL COMPROBANTES:</b>  '.$ad_cont,
		                  'total2'=>'<b>MONTO TOTAL COMPROBANTES Bs.:</b>  '.$ad_total);
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>600, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>290),
						 			   'total2'=>array('justification'=>'right','width'=>270))); // Justificación y ancho de la 
		
		
		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------


	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();	
	require_once("../class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$ls_tiporeporte="0";
	$ls_bolivares="";

	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $ls_compdes=$_GET["txtcompdes"];
	 $ls_comphas=$_GET["txtcomphas"];
	 $ls_procdes=$_GET["txtprocdes"];
	 $ls_prochas=$_GET["txtprochas"];
	 $fecdes=$_GET["txtfecdes"];
	 if (!empty($fecdes))
	 {
	     $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
	 }	else {  $ldt_fecdes=""; } 
	 $fechas=$_GET["txtfechas"];
	 if (!empty($fechas))
	 {
  	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
	 }	else {  $ldt_fechas=""; } 
	
	 $ls_orden=$_GET["rborden"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		require_once("sigesp_scg_reporte.php");
		$io_report  = new sigesp_scg_reporte();
		
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ldt_fecdes=substr($ldt_fecdes,0,10);
		$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar($ldt_fecdes);
		$ldt_fechas=substr($ldt_fechas,0,10);
		$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar($ldt_fechas);
		$ldt_fecha_cab=" <b>Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab." </b>"  ;
		$ls_titulo=" <b>RESUMEN DE COMPROBANTES </b> ";
			
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=uf_insert_seguridad("<b>RESUMEN DE COMPROBANTES</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		 $lb_valido=$io_report->uf_scg_reporte_select_comprobante_formato1($ls_procdes,$ls_prochas,$ls_compdes,$ls_comphas,$ldt_fecdes,
	                                                                   $ldt_fechas,$ls_orden);
	}																	  
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,3,3,4.5); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ldt_fecha_cab,$io_pdf); // Imprimimos el encabezado de la página
		print_cabecera($io_pdf);
		$li_tot=$io_report->dts_cab->getRowCount("comprobante");
		$ld_total=0;
		$ld_debe=0;
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ld_totaldebe=0;
			$ld_totalhaber=0;
			$ls_comprobante=$io_report->dts_cab->data["comprobante"][$li_i];
			$ldt_fecha=$io_report->dts_cab->data["fecha"][$li_i];
			$ls_procede=$io_report->dts_cab->data["procede"][$li_i];
			$ls_descripcion=$io_report->dts_cab->data["descripcion"][$li_i];		    
			$ldt_fec=$io_funciones->uf_convertirfecmostrar($ldt_fecha);
			
			$lb_valido=$io_report->uf_scg_reporte_comprobante_formato1($ls_procede,$ls_comprobante,$ldt_fecha,$ls_orden);
			if($lb_valido)
			{
				$li_totrow_det=$io_report->dts_reporte->getRowCount("comprobante");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_debhab=$io_report->dts_reporte->data["debhab"][$li_s];
					$ld_monto=$io_report->dts_reporte->data["monto"][$li_s];
					
					if($ls_debhab=='D')
					{
					   $ld_debe=number_format($ld_monto,2,",",".");
					   $ld_totaldebe=$ld_totaldebe+$ld_monto;
					   
					}
			   }
			   
			   $ld_total=$ld_total+$ld_totaldebe;
			   
			}			
			$ld_totaldebe=number_format($ld_totaldebe,2,",",".");
			$la_data[$li_i]=array('fecha'=>$ldt_fec,'procede'=>$ls_procede,'numero'=>$ls_comprobante,'tipo'=>$ls_descripcion,'monto'=>$ld_totaldebe);
			$ld_totaldebe=0;	
			
			
       }
	    
		uf_print_detalle($la_data,$io_pdf);
		$ld_total=number_format($ld_total,2,",",".");
		uf_print_total($ld_total,$li_tot,$io_pdf);				
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->ezStream(); // Mostramos el reporte	
		
		
	}
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);			
?> 