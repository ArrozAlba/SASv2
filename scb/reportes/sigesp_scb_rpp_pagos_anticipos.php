<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";		
		print "close();";
		print "</script>";		
	}
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 08/10/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();		
		$io_pdf->line(20,40,730,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,515,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título		
		$io_pdf->addText(700,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(705,550,7,date("h:i a")); // Agregar la Hora
		$io_pdf->addText(20,30,8,"C: DOCUMENTOS CONTABILIZADOS");
		$io_pdf->addText(200,30,8,"N: DOCUMENTOS POR CONTABILIZAR");
		$io_pdf->addText(400,30,8,"A: DOCUMENTOS ANULADOS");
		$io_pdf->addText(600,30,8,"O: DOCUMENTOS ORIGINAL");		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo, $as_nombre, $titulo, &$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle  por página
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 08/10/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data_titulo[1]=array('titulo'=>'<b>'.$titulo.'</b>',
		                         'codigo'=>'<b>'.$as_codigo.'</b>',
							     'nombre'=>'<b>'.$as_nombre.'</b>');
		$la_columnas=array('titulo'=>'',
		                   'codigo'=>'',
						   'nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna					 			  
									   'nombre'=>array('justification'=>'left','width'=>490))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);		
		
		$la_data=array(array('numdoc'=>'<b>Número del Documento</b>',
		                     'fecha'=>'<b>Fecha</b>',
							 'tipo'=>'<b>Tipo</b>',
							 'concepto'=>'<b>Concepto</b>',
							 'debe'=>'<b>Debe</b>',
							 'haber'=>'<b>Haber</b>',
							 'contable'=>'<b>Estatus</b>'));
		$la_columnas=array('numdoc'=>'',
		                   'fecha'=>'',
						   'tipo'=>'',
						   'concepto'=>'',
						   'debe'=>'',
						   'haber'=>'',
						   'contable'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numdoc'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>80),// Justificación y ancho de la columna
									   'concepto'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'center','width'=>80),
									   'haber'=>array('justification'=>'center','width'=>80),
									   'contable'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// fin de  uf_print_cabecera
//-----------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle($as_numdoc,$as_fecha,$as_tipo, $as_concepto, $as_debe, $as_haber,$as_contable, &$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle  por página
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 08/10/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('numdoc'=>$as_numdoc,
		                  'fecha'=>$as_fecha,
						  'tipo'=>$as_tipo,
						  'concepto'=>$as_concepto,
						  'debe'=>$as_debe,
                          'haber'=>$as_haber,
						  'contable'=> $as_contable);
		$la_columnas=array('numdoc'=>'',
		                   'fecha'=>'',
						   'tipo'=>'',
						   'concepto'=>'',
						   'debe'=>'',
						   'haber'=>'',
						   'contable'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numdoc'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>80),// Justificación y ancho de la columna
									   'concepto'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'center','width'=>80),
									   'haber'=>array('justification'=>'center','width'=>80),
									   'contable'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// fin de  uf_print_cabecera
//----------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_total($as_totaldeb, $as_totalhab,$as_saldo,&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el total  por página
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 08/10/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('saldo'=>'<b>Saldo del Anticipo: </b>'.$as_saldo,'debe'=>$as_totaldeb,'haber'=>$as_totalhab,'nada'=>'');
		$la_columnas=array('saldo'=>'','debe'=>'','haber'=>'','nada'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('saldo'=>array('justification'=>'right','width'=>460),
						 			   'debe'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'center','width'=>80),
									   'nada'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// fin de  uf_print_cabecera
//----------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_total_general($as_totaldeb, $as_totalhab,$as_saldo,&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el total  por página
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 08/10/2008 		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data[1]=array('saldo'=>'<b>Total General Saldo del Anticipo : </b>'.$as_saldo,'debe'=>$as_totaldeb,
		                  'haber'=>$as_totalhab,'nada'=>'');
		$la_columnas=array('saldo'=>'','debe'=>'','haber'=>'','nada'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol2'=>array(0.7,0.7,0.7), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('saldo'=>array('justification'=>'right','width'=>460),
						 			   'debe'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'center','width'=>80),
									   'nada'=>array('justification'=>'center','width'=>50))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);		
	}// fin de  uf_print_cabecera
//---------------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_scb_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	$sig_inc   = new sigesp_include();
	$con       = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	$io_sql    = new class_sql($con);
	$ls_titulo = "Listado de Pagos de Anticipos ";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecdesde    = $_GET["fecdes"];
	$ld_fechasta    = $_GET["fechas"];	
	$ls_orden	    = $_GET["orden"];
	$ls_codope	    = $_GET["operacion"]; 
	$ls_probendesde = $_GET["probendes"];
	$ls_probenhasta = $_GET["probenhas"];
	$ls_tipproben   = $_GET["tipproben"];
	if ($ls_tipproben=="P")
	{
		$ls_cod_prodes=$ls_probendesde;
		$ls_cod_prohas=$ls_probenhasta;
        $ls_cedbebdes='----------';
		$ls_cedbebhas='----------';		
	}
	else
	{
		$ls_cod_prodes='----------';
		$ls_cod_prohas='----------';
        $ls_cedbebdes=$ls_probendesde;
		$ls_cedbebhas=$ls_probenhasta;
	}
	$lb_valido  = true;
	$ls_titulo="<b>LISTADO DE PAGO DE ANTICIPOS/AMORTIZACIONES</b>";	
	$lb_valido=$io_report->select_anticipos_amortizacion($ls_cod_prodes, $ls_cod_prohas ,$ls_cedbebdes, $ls_cedbebhas,
	                                                     $ld_fecdesde, $ld_fechasta, $ls_orden);	
	if  ($lb_valido)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros	
		$io_pdf->ezStartPageNumbers(735,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,&$io_pdf);	
		$total=$io_report->ds_data->getRowCount("numdoc"); 
		$codaux="";
		$ls_totaldebe=0;
		$ls_totalhaber=0;
		$total_debe=0;
		$total_haber=0;
		$total_saldo=0;
		for ($i=1; $i<=$total; $i++)
		{
			$ls_codpro=$io_report->ds_data->data["codpro"][$i];
			$ls_nompro=$io_report->ds_data->data["nompro"][$i];
			$ls_cedbene=$io_report->ds_data->data["cedbene"][$i];
			$ls_nombene=$io_report->ds_data->data["nombene"][$i];
			if ($ls_tipproben=="P")
			{   
			   	if ($codaux!=$ls_codpro)
				{
				    if ($codaux!="")
					{
					    uf_print_total(number_format($ls_totaldebe,2,",","."),
						               number_format($ls_totalhaber,2,",","."),
									   number_format($ls_saldo,2,",","."),
									   &$io_pdf);
						$ls_totaldebe=0;
					    $ls_totalhaber=0;
					}
					$codaux=$ls_codpro;
					$io_pdf->ezSetDy(-20);
					uf_print_cabecera($ls_codpro, $ls_nompro, 'PROVEEDOR', &$io_pdf);					
				}
			}
			else
			{
				if ($codaux!=$ls_cedbene)
				{
					if ($codaux!="")
					{
						uf_print_total(number_format($ls_totaldebe,2,",","."),
						               number_format($ls_totalhaber,2,",","."),
									   number_format($ls_saldo,2,",","."),
									   &$io_pdf);
						$ls_totaldebe=0;
					    $ls_totalhaber=0;
					}
					$codaux=$ls_cedbene;
					uf_print_cabecera($ls_cedbene, $ls_cedbene, 'BENEFICIARIO', &$io_pdf);
					
				}
			}			
			$ls_numdoc=$io_report->ds_data->data["numdoc"][$i];
			$ls_fecha=$io_report->ds_data->data["fecha"][$i];  
			$ls_tipo=$io_report->ds_data->data["tipo"][$i];  
			$ls_concepto=$io_report->ds_data->data["concepto"][$i]; 
			$ls_debhab=$io_report->ds_data->data["debhab"][$i];
			$ls_monto=$io_report->ds_data->data["monto"][$i];
			
			
			$ls_saldo=$io_report->ds_data->data["saldo"][$i]; 
			$ls_estatus=$io_report->ds_data->data["estmov"][$i];
			
			if ($ls_estatus=="N")
			{
				$ls_contable="N";
			}
			elseif ($ls_estatus=="C")
			{
				$ls_contable="C";
			}
			elseif ($ls_estatus=="A")
			{
				$ls_contable="A";
			}elseif ($ls_estatus=="O")
			{
				$ls_contable="O";
			}
			
			if ($ls_debhab=="D")
			{
				$ls_montodebe=$ls_monto;
				$ls_totaldebe=$ls_totaldebe+$ls_monto; 
				$ls_montohab=0;
				$total_saldo=$total_saldo+$ls_saldo;
				$total_debe=$total_debe+$ls_monto;
			}
			else
			{
				$ls_montodebe=0;
				$ls_montohab=$ls_monto;
				$ls_totalhaber=$ls_totalhaber+$ls_monto;
				$total_haber=$total_haber+$ls_monto;
				
			}
			
			$ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
			uf_print_detalle($ls_numdoc,$ls_fecha,$ls_tipo, $ls_concepto,
			                 number_format($ls_montodebe,2,",","."), 
			                 number_format($ls_montohab,2,",","."), $ls_contable,&$io_pdf);				
		}// fin del for
		if ($total>0)
		{	
			uf_print_total(number_format($ls_totaldebe,2,",","."),
						   number_format($ls_totalhaber,2,",","."),
						   number_format($ls_saldo,2,",","."),
						   &$io_pdf);
			uf_print_total_general(number_format($total_debe,2,",","."),
								   number_format($total_haber,2,",","."),
								   number_format($total_saldo,2,",","."),
								   &$io_pdf);
		}		
		if(($lb_valido)&&($total>0)) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");	
	}// fin del else	
?> 