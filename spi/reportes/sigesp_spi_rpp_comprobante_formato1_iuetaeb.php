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
	function uf_print_encabezado_pagina($as_titulo,$as_periodo_comp,$as_fecha_comp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=320-($li_tm/2);
		$io_pdf->addText($tm,698,10,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(10,$as_periodo_comp);
		$tm=320-($li_tm/2);
		$io_pdf->addText($tm,685,10,$as_periodo_comp); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(10,$as_fecha_comp);
		$tm=320-($li_tm/2);
		$io_pdf->addText($tm,670,10,$as_fecha_comp); // Agregar el título
		
		$io_pdf->addText(500,720,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_procede,$as_comprobante,$as_nomprobene,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_procede // procede
		//	    		   as_comprobante // comprobante
		//                 as_nomprobene   // nombre del proveedor
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Comprobante</b>  '.$as_procede.'---'.$as_comprobante.''),
		               array('name'=>'<b>Proveedor</b>  '.$as_nomprobene.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>299); // Orientación de la tabla 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/09/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'dencuenta'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la 
						 			   'descripcion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'operacion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la 
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'dencuenta'=>'<b>Denominacion Cuenta</b>',
						   'descripcion'=>'<b>Descripción Movimiento</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'operacion'=>'<b>Operacion</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_comprobante($ad_totalcomprobante,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalcomprobante // Total Comprobante
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('cuenta'=>' ','dencuenta'=>' ','descripcion'=>' ','fecha'=>' ',
		                  'operacion'=>'<b>Total Comprobante </b>','monto'=>$ad_totalcomprobante);
		$la_columnas=array('cuenta'=>'','dencuenta'=>'','descripcion'=>'','fecha'=>'','operacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'dencuenta'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la 
						 			   'descripcion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la 
						 			   'operacion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>'<b>Total</b>','monto'=>$ad_total));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>150))); // Justificación y ancho de la 

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//----------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_print_pie_de_pagina(&$io_pdf)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	   Function: uf_print_pie_de_pagina
	//	     Access: public
	//	    Returns: vacio	 
	//	Description: Método que imprime el pie de pagina de Forma 0301 De Modificaciones Presupuestarias. 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
          
       
		$io_pdf->line(220,100,400,100);	
		$io_pdf->addText(170,103,7,"Elaborado por:");
		$io_pdf->line(220,50,400,50);	
		//$io_pdf->line(480,40,480,100);	
		$io_pdf->addText(170,53,7,"Revisado por:");
			
	}// 
	
	
	
	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_spi_reporte.php");
	$io_report = new sigesp_spi_reporte();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../class_funciones_ingreso.php");
	$io_fun_ingreso=new class_funciones_ingreso();			
//--------------------------------------------------  Parámetros para Filtar el Reporte  -------------------------------------
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
	
	 $ls_orden_select=$_GET["rborden"];
//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
	$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	$li_ano=substr($ldt_periodo,0,4);
	
	$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar(substr($ldt_fecdes,0,10));
	$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar(substr($ldt_fechas,0,10));
	
	$ls_titulo=" <b>COMPROBANTE PRESUPUESTARIO DE INGRESO</b> ";       
	$ls_periodo_comp=" <b>Comprobante del  ".$ls_compdes."  --  ".$ls_procdes."   al  ".$ls_comphas."  --  ".$ls_prochas."  </b>  ";
	$ls_fecha_comp=" <b>Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab." </b>";
	
	$ls_tiporeporte=$_GET["tiporeporte"];
		global $ls_tiporeporte;
		require_once("../../shared/ezpdf/class.ezpdf.php");
		
		if($ls_tiporeporte==1)
		{
			require_once("sigesp_spi_reportebsf.php");
			$io_report=new sigesp_spi_reportebsf();
		}    

	/////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_desc_event="Solicitud del Reporte de Comprobante del  ".$ls_compdes."  --  ".$ls_procdes."   al  ".$ls_comphas."  --  ".$ls_prochas."   Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab;
	$io_fun_ingreso->uf_load_seguridad_reporte("SPI","sigesp_spi_r_comprobante_formato1.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=$io_report->uf_spi_reporte_select_comprobante_formato1($ls_procdes,$ls_prochas,$ls_compdes,$ls_comphas,$ldt_fecdes,$ldt_fechas,$ls_orden_select);
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
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_periodo_comp,$ls_fecha_comp,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("comprobante");
		$ld_total=0; 
	    $ld_totalcomprobante=0;
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_comprobante=$io_report->dts_cab->data["comprobante"][$li_i];
			$ls_procede=$io_report->dts_cab->data["procede"][$li_i];
			$ls_ced_bene=$io_report->dts_cab->data["ced_bene"][$li_i];
			$ls_cod_pro=$io_report->dts_cab->data["cod_pro"][$li_i];
			$ls_nompro=$io_report->dts_cab->data["nompro"][$li_i];
			$ls_apebene=$io_report->dts_cab->data["apebene"][$li_i];
			$ls_nombene=$io_report->dts_cab->data["nombene"][$li_i];
			$ls_tipo_destino=$io_report->dts_cab->data["tipo_destino"][$li_i];
		    if($ls_tipo_destino=="P")
		    {
			    $ls_nomprobene=$ls_nompro;  
		    }
			if($ls_tipo_destino=="B")
			{   
			   if ($ls_apebene!="")
				 {$ls_nomprobene=$ls_apebene.", ".$ls_nombene;}
				else
				 {$ls_nomprobene=$ls_nombene; }
			}
			if($ls_tipo_destino=="-")
			{
				$ls_nomprobene="";
			}	
			uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_spi_reporte_comprobante_formato1($ls_procede,$ls_procede,$ls_comprobante,$ls_comprobante,$ldt_fecdes,$ldt_fechas,$ls_orden_select);
            if($lb_valido)
			{
				$li_totrow_det=$io_report->dts_reporte->getRowCount("comprobante");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
					$ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
					$fecha=$io_report->dts_reporte->data["fecha"][$li_s];
					$ldt_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
					$ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
					$ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
					$ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
					$ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];
					$ld_monto=$io_report->dts_reporte->data["monto"][$li_s];
					$ls_orden=$io_report->dts_reporte->data["orden"][$li_s];
					$ls_dencuenta=$io_report->dts_reporte->data["dencuenta"][$li_s];
					$ls_denoperacion=$io_report->dts_reporte->data["denoperacion"][$li_s];
					$ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
					$ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
					$ls_ced_bene=$io_report->dts_reporte->data["ced_bene"][$li_s];
					$ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
					$ls_apebene=$io_report->dts_reporte->data["apebene"][$li_s];
					$ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];		
					  
					   if($ls_apebene!="")
					     {$ls_apebene=$ls_apebene;}
					   else
					     {$ls_apebene="";}
					
					$ld_totalcomprobante=$ld_totalcomprobante+$ld_monto;
					$ld_total=$ld_total+$ld_monto;
					
					if($ld_monto<0)
					{
					  $ld_monto_positivo=abs($ld_monto);
					  $ld_monto=number_format($ld_monto_positivo,2,",",".");
					  $ld_monto="(".$ld_monto.")";
					}
					else
					{
					  $ld_monto=number_format($ld_monto,2,",",".");
					}
					$la_data[$li_s]=array('cuenta'=>$ls_spi_cuenta,'dencuenta'=>$ls_dencuenta,'descripcion'=>$ls_descripcion,
					                      'fecha'=>$ldt_fecha,'operacion'=>$ls_denoperacion,'monto'=>$ld_monto);		  
					
					$ld_monto=str_replace('.','',$ld_monto);
					$ld_monto=str_replace(',','.',$ld_monto);
					
				}
			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				if($ld_totalcomprobante<0)
				{
				  $ld_monto_positivo=abs($ld_totalcomprobante);
				  $ld_totalcomprobante=number_format($ld_monto_positivo,2,",",".");
				  $ld_totalcomprobante="(".$ld_totalcomprobante.")";
				}
				else
				{
			       $ld_totalcomprobante=number_format($ld_totalcomprobante,2,",",".");
				}
			    $ld_totalcomprob=$ld_totalcomprobante;
			    uf_print_total_comprobante($ld_totalcomprobante,$io_pdf); // Imprimimos el total comprobante
			    $ld_totalcomprobante=0;
 			}
          	if ($io_pdf->ezPageCount==$thisPageNum)
			{// Hacemos el commit de los registros que se desean imprimir
            	$io_pdf->transaction('commit');
          	}
			elseif($thisPageNum>1)
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva página
			    uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$io_pdf); // Imprimimos la cabecera del registro
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			    uf_print_total_comprobante($ld_totalcomprob,$io_pdf); // Imprimimos el total comprobante
			    $ld_totalcomprob=0;
			}
			if($li_i==$li_tot)
			{
			  $ld_total=number_format($ld_total,2,",",".");
			  uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos pie de la cabecera
			}
			unset($la_data);			
		}//for
		uf_print_pie_de_pagina($io_pdf);
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